<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $deal->title ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="client_id" value="Client" />
        <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="">Select a client</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $deal->client_id ?? '') == $client->id)>{{ $client->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pipeline_stage_id" value="Pipeline Stage" />
        <select id="pipeline_stage_id" name="pipeline_stage_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="">Select a stage</option>
            @foreach($stages as $stage)
                <option value="{{ $stage->id }}" @selected(old('pipeline_stage_id', $deal->pipeline_stage_id ?? '') == $stage->id)>{{ $stage->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pipeline_stage_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="value" value="Value ($)" />
        <x-text-input id="value" name="value" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('value', $deal->value ?? '')" required />
        <x-input-error :messages="$errors->get('value')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="probability" value="Probability" />
        <select id="probability" name="probability" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach(['0', '25', '50', '75', '100'] as $value)
                <option value="{{ $value }}" @selected(old('probability', $deal->probability ?? '50') == $value)>{{ $value }}%</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('probability')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach(['open' => 'Open', 'won' => 'Won', 'lost' => 'Lost'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $deal->status ?? 'open') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="expected_close_date" value="Expected Close Date" />
        <x-text-input id="expected_close_date" name="expected_close_date" type="date" class="mt-1 block w-full" :value="old('expected_close_date', optional($deal->expected_close_date ?? null)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('expected_close_date')" class="mt-2" />
    </div>

    @if(isset($deal))
        <div>
            <x-input-label for="closed_date" value="Closed Date" />
            <x-text-input id="closed_date" name="closed_date" type="date" class="mt-1 block w-full" :value="old('closed_date', optional($deal->closed_date)->format('Y-m-d'))" />
            <x-input-error :messages="$errors->get('closed_date')" class="mt-2" />
        </div>
    @endif

    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $deal->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>
