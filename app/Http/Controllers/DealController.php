<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deal;
use App\Models\PipelineStage;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $stages = PipelineStage::where('is_active', true)
            ->orderBy('order')
            ->with(['deals' => function ($query) {
                $query->with('client', 'assignedUser')->latest();
            }])
            ->get();

        return view('deals.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $stages = PipelineStage::where('is_active', true)->orderBy('order')->get();

        return view('deals.create', compact('clients', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'value' => 'required|numeric|min:0',
            'probability' => 'required|in:0,25,50,75,100',
            'expected_close_date' => 'nullable|date',
            'status' => 'required|in:open,won,lost',
            'description' => 'nullable|string',
        ]);

        $validated['assigned_to'] = auth()->id();

        Deal::create($validated);

        return redirect()->route('deals.index')->with('success', 'Deal created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Deal $deal)
    {
        $deal->load('client', 'pipelineStage', 'assignedUser', 'payments', 'followUps');

        return view('deals.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deal $deal)
    {
        $clients = Client::orderBy('name')->get();
        $stages = PipelineStage::where('is_active', true)->orderBy('order')->get();

        return view('deals.edit', compact('deal', 'clients', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'value' => 'required|numeric|min:0',
            'probability' => 'required|in:0,25,50,75,100',
            'expected_close_date' => 'nullable|date',
            'closed_date' => 'nullable|date',
            'status' => 'required|in:open,won,lost',
            'description' => 'nullable|string',
        ]);

        $deal->update($validated);

        return redirect()->route('deals.index')->with('success', 'Deal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deal $deal)
    {
        $deal->delete();

        return redirect()->route('deals.index')->with('success', 'Deal deleted successfully.');
    }
}
