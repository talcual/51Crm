<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::with('client', 'deal')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('quotes.index', compact('quotes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('title')->get();

        return view('quotes.create', compact('clients', 'deals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateQuote($request);

        $quote = Quote::create($this->buildQuoteData($validated) + [
            'quote_number' => $this->generateQuoteNumber(),
            'created_by' => auth()->id(),
        ]);

        $this->syncItems($quote, $validated['items']);

        return redirect()->route('quotes.index')->with('success', 'Quote created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        $quote->load('client', 'deal', 'items', 'createdBy');

        return view('quotes.show', compact('quote'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        $quote->load('items');
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('title')->get();

        return view('quotes.edit', compact('quote', 'clients', 'deals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quote $quote)
    {
        $validated = $this->validateQuote($request);

        $quote->update($this->buildQuoteData($validated));

        $quote->items()->delete();
        $this->syncItems($quote, $validated['items']);

        return redirect()->route('quotes.index')->with('success', 'Quote updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        return redirect()->route('quotes.index')->with('success', 'Quote deleted successfully.');
    }

    /**
     * Generate a PDF for the specified quote.
     */
    public function generatePdf(Quote $quote)
    {
        $quote->load('client', 'deal', 'items');

        $pdf = Pdf::loadView('quotes.pdf', compact('quote'));

        return $pdf->stream("quote-{$quote->quote_number}.pdf");
    }

    private function validateQuote(Request $request): array
    {
        return $request->validate([
            'client_id' => 'required|exists:clients,id',
            'deal_id' => 'nullable|exists:deals,id',
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'valid_until' => 'nullable|date',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
    }

    private function buildQuoteData(array $validated): array
    {
        $subtotal = collect($validated['items'])->sum(fn ($item) => $item['quantity'] * $item['unit_price']);
        $tax = $validated['tax'] ?? 0;
        $discount = $validated['discount'] ?? 0;

        return [
            'client_id' => $validated['client_id'],
            'deal_id' => $validated['deal_id'] ?? null,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $subtotal + $tax - $discount,
            'status' => $validated['status'],
            'valid_until' => $validated['valid_until'] ?? null,
            'terms' => $validated['terms'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];
    }

    private function syncItems(Quote $quote, array $items): void
    {
        foreach ($items as $item) {
            $quote->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }
    }

    private function generateQuoteNumber(): string
    {
        $nextId = (Quote::max('id') ?? 0) + 1;

        return 'QT-' . now()->format('Ymd') . '-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}
