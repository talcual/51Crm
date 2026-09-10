<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Campaign Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $campaign->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="recipient_type" value="Recipients" />
        <select id="recipient_type" name="recipient_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required onchange="document.getElementById('custom-recipients-wrapper').classList.toggle('hidden', this.value !== 'custom')">
            @foreach(['all_clients' => 'All Clients', 'all_leads' => 'All Leads', 'custom' => 'Custom List'] as $value => $label)
                <option value="{{ $value }}" @selected(old('recipient_type', $campaign->recipient_type ?? 'all_clients') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('recipient_type')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="subject" value="Subject" />
        <x-text-input id="subject" name="subject" type="text" class="mt-1 block w-full" :value="old('subject', $campaign->subject ?? '')" required />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
    </div>

    <div id="custom-recipients-wrapper" class="md:col-span-2 {{ old('recipient_type', $campaign->recipient_type ?? 'all_clients') === 'custom' ? '' : 'hidden' }}">
        <x-input-label for="custom_recipients" value="Custom Recipients (one email per line or comma-separated)" />
        <textarea id="custom_recipients" name="custom_recipients" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('custom_recipients', $campaign->custom_recipients ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('custom_recipients')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="content" value="Email Content (HTML supported)" />
        <textarea id="content" name="content" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 font-mono text-sm" required>{{ old('content', $campaign->content ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="scheduled_at" value="Schedule For (optional)" />
        <x-text-input id="scheduled_at" name="scheduled_at" type="datetime-local" class="mt-1 block w-full" :value="old('scheduled_at', optional($campaign->scheduled_at ?? null)->format('Y-m-d\TH:i'))" />
        <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
    </div>
</div>
