@php
    $statuses = ['new' => 'New', 'contacted' => 'Contacted', 'qualified' => 'Qualified', 'lost' => 'Lost', 'converted' => 'Converted'];
    $sources = ['website' => 'Website', 'referral' => 'Referral', 'social_media' => 'Social Media', 'email' => 'Email', 'phone' => 'Phone', 'other' => 'Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $lead->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $lead->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $lead->phone ?? '')" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="company" value="Company" />
        <x-text-input id="company" name="company" type="text" class="mt-1 block w-full" :value="old('company', $lead->company ?? '')" />
        <x-input-error :messages="$errors->get('company')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $lead->status ?? 'new') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="source" value="Source" />
        <select id="source" name="source" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            @foreach($sources as $value => $label)
                <option value="{{ $value }}" @selected(old('source', $lead->source ?? 'website') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('source')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="estimated_value" value="Estimated Value ($)" />
        <x-text-input id="estimated_value" name="estimated_value" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('estimated_value', $lead->estimated_value ?? '')" />
        <x-input-error :messages="$errors->get('estimated_value')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $lead->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>
