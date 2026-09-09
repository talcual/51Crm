<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('client', 'deal')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('title')->get();

        return view('payments.create', compact('clients', 'deals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validatePayment($request);

        $validated['payment_reference'] = $this->generatePaymentReference();

        Payment::create($validated);

        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load('client', 'deal', 'loyaltyPoints');

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $clients = Client::orderBy('name')->get();
        $deals = Deal::orderBy('title')->get();

        return view('payments.edit', compact('payment', 'clients', 'deals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $this->validatePayment($request);

        $payment->update($validated);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    private function validatePayment(Request $request): array
    {
        return $request->validate([
            'client_id' => 'required|exists:clients,id',
            'deal_id' => 'nullable|exists:deals,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_method' => 'required|in:credit_card,bank_transfer,cash,financing',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
        ]);
    }

    private function generatePaymentReference(): string
    {
        $nextId = (Payment::max('id') ?? 0) + 1;

        return 'PAY-' . now()->format('Ymd') . '-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }
}
