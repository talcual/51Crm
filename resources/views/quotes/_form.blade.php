@php
    $existingItems = old('items', isset($quote) ? $quote->items->map(fn($item) => [
        'description' => $item->description,
        'quantity' => $item->quantity,
        'unit_price' => $item->unit_price,
    ])->toArray() : []);

    if (empty($existingItems)) {
        $existingItems = [['description' => '', 'quantity' => 1, 'unit_price' => '']];
    }
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="client_id" value="Client" />
        <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="">Select a client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $quote->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="deal_id" value="Related Deal (optional)" />
        <select id="deal_id" name="deal_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">None</option>
            @foreach($deals as $deal)
                <option value="{{ $deal->id }}" @selected(old('deal_id', $quote->deal_id ?? '') == $deal->id)>{{ $deal->title }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('deal_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach(['draft' => 'Draft', 'sent' => 'Sent', 'accepted' => 'Accepted', 'rejected' => 'Rejected', 'expired' => 'Expired'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $quote->status ?? 'draft') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="valid_until" value="Valid Until" />
        <x-text-input id="valid_until" name="valid_until" type="date" class="mt-1 block w-full" :value="old('valid_until', optional($quote->valid_until ?? null)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('valid_until')" class="mt-2" />
    </div>
</div>

<div class="mt-6">
    <h3 class="text-sm font-medium text-gray-700 mb-2">Line Items</h3>
    <div id="quote-items" class="space-y-3">
        @foreach($existingItems as $index => $item)
            <div class="quote-item grid grid-cols-12 gap-2 items-start">
                <div class="col-span-6">
                    <x-text-input name="items[{{ $index }}][description]" type="text" class="block w-full" placeholder="Description" value="{{ $item['description'] }}" required />
                </div>
                <div class="col-span-2">
                    <x-text-input name="items[{{ $index }}][quantity]" type="number" min="1" class="block w-full" placeholder="Qty" value="{{ $item['quantity'] }}" required />
                </div>
                <div class="col-span-3">
                    <x-text-input name="items[{{ $index }}][unit_price]" type="number" step="0.01" min="0" class="block w-full" placeholder="Unit Price" value="{{ $item['unit_price'] }}" required />
                </div>
                <div class="col-span-1 flex items-center pt-2">
                    <button type="button" class="text-red-600 hover:text-red-900 remove-item">&times;</button>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" id="add-item" class="mt-3 inline-flex items-center px-3 py-1.5 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
        Add Line Item
    </button>
    <x-input-error :messages="$errors->get('items')" class="mt-2" />
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div>
        <x-input-label for="tax" value="Tax ($)" />
        <x-text-input id="tax" name="tax" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('tax', $quote->tax ?? 0)" />
        <x-input-error :messages="$errors->get('tax')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="discount" value="Discount ($)" />
        <x-text-input id="discount" name="discount" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('discount', $quote->discount ?? 0)" />
        <x-input-error :messages="$errors->get('discount')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="terms" value="Terms" />
        <textarea id="terms" name="terms" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('terms', $quote->terms ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('terms')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $quote->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>

<script>
    (function () {
        const container = document.getElementById('quote-items');
        const addButton = document.getElementById('add-item');
        let index = {{ count($existingItems) }};

        function rowTemplate(i) {
            const div = document.createElement('div');
            div.className = 'quote-item grid grid-cols-12 gap-2 items-start';
            div.innerHTML = `
                <div class="col-span-6"><input type="text" name="items[${i}][description]" placeholder="Description" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" /></div>
                <div class="col-span-2"><input type="number" min="1" name="items[${i}][quantity]" placeholder="Qty" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" /></div>
                <div class="col-span-3"><input type="number" step="0.01" min="0" name="items[${i}][unit_price]" placeholder="Unit Price" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" /></div>
                <div class="col-span-1 flex items-center pt-2"><button type="button" class="text-red-600 hover:text-red-900 remove-item">&times;</button></div>
            `;
            return div;
        }

        addButton.addEventListener('click', function () {
            container.appendChild(rowTemplate(index));
            index++;
        });

        container.addEventListener('click', function (event) {
            if (event.target.classList.contains('remove-item')) {
                if (container.querySelectorAll('.quote-item').length > 1) {
                    event.target.closest('.quote-item').remove();
                }
            }
        });
    })();
</script>
