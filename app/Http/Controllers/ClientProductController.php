<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientProduct;
use Illuminate\Http\Request;

class ClientProductController extends Controller
{
    /**
     * Associate a purchased product with the client.
     */
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        $client->clientProducts()->create($validated);

        return redirect()->route('clients.show', $client)->with('success', 'Product added to client successfully.');
    }

    /**
     * Remove a purchased product from the client.
     */
    public function destroy(Client $client, ClientProduct $clientProduct)
    {
        abort_unless($clientProduct->client_id === $client->id, 404);

        $clientProduct->delete();

        return redirect()->route('clients.show', $client)->with('success', 'Product removed from client successfully.');
    }
}
