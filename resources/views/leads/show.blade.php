<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $lead->name }}
            </h2>
            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                {{ __('Edit') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $lead->email ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $lead->phone ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Company') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $lead->company ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst($lead->status)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Source') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst(str_replace('_', ' ', $lead->source))) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Estimated Value') }}</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($lead->estimated_value ?? 0, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Assigned To') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $lead->assignedUser->name ?? __('Unassigned') }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $lead->notes ?? __('N/A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">{{ __('Follow-Ups') }}</h3>
                    @forelse($lead->followUps as $followUp)
                        <div class="border-b border-gray-200 py-2 text-sm text-gray-700">
                            {{ $followUp->title }}
                            @if($followUp->description)
                                <span class="text-gray-500">— {{ $followUp->description }}</span>
                            @endif
                            <span class="text-gray-400">— {{ $followUp->created_at->format('M d, Y') }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('No follow-ups recorded yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-4">{{ __('Quotes') }} ({{ $lead->quotes->count() }})</h3>
                    @forelse($lead->quotes as $quote)
                        <div class="border-b border-gray-200 py-2 text-sm text-gray-700 flex justify-between">
                            <a href="{{ route('quotes.show', $quote) }}" class="text-blue-600 hover:text-blue-900">{{ $quote->quote_number }}</a>
                            <span class="text-gray-500">${{ number_format($quote->total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">{{ __('No quotes yet.') }}</p>
                    @endforelse
                    <div class="mt-3">
                        <a href="{{ route('quotes.create') }}" class="text-sm text-blue-600 hover:text-blue-900">{{ __('Create a quote for this lead') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
