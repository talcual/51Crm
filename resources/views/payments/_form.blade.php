<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="client_id" value="Client" />
        <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="">Select a client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $payment->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="deal_id" value="Related Deal (optional)" />
        <select id="deal_id" name="deal_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">None</option>
            @foreach($deals as $deal)
                <option value="{{ $deal->id }}" @selected(old('deal_id', $payment->deal_id ?? '') == $deal->id)>{{ $deal->title }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('deal_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="amount" value="Amount ($)" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('amount', $payment->amount ?? '')" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="payment_date" value="Payment Date" />
        <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', optional($payment->payment_date ?? null)->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach(['pending' => 'Pending', 'completed' => 'Completed', 'failed' => 'Failed', 'refunded' => 'Refunded'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $payment->status ?? 'pending') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="payment_method" value="Payment Method" />
        <select id="payment_method" name="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach(['credit_card' => 'Credit Card', 'bank_transfer' => 'Bank Transfer', 'cash' => 'Cash', 'financing' => 'Financing'] as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_method', $payment->payment_method ?? 'credit_card') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="transaction_id" value="Transaction ID" />
        <x-text-input id="transaction_id" name="transaction_id" type="text" class="mt-1 block w-full" :value="old('transaction_id', $payment->transaction_id ?? '')" />
        <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $payment->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>
