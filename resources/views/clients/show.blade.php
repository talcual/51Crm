<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Client Details') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('General Information') }}</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Phone') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->phone ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Company') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->company ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Type') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst($client->type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Assigned To') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->assignedUser->name ?? __('Unassigned') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Address') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->address ?? __('N/A') }}, {{ $client->city ?? '' }} {{ $client->state ?? '' }} {{ $client->postal_code ?? '' }} {{ $client->country ?? '' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Originated From Lead') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $client->lead->name ?? __('N/A') }}</dd>
                        </div>
                        @if($client->notes)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                                <dd class="text-sm text-gray-900">{{ $client->notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Deals') }} ({{ $client->deals->count() }})</h3>
                        <ul class="space-y-2">
                            @forelse($client->deals as $deal)
                                <li class="text-sm text-gray-700 flex justify-between">
                                    <span>{{ $deal->title }}</span>
                                    <span class="text-gray-500">${{ number_format($deal->value, 2) }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">{{ __('No deals yet.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Quotes') }} ({{ $client->quotes->count() }})</h3>
                        <ul class="space-y-2">
                            @forelse($client->quotes as $quote)
                                <li class="text-sm text-gray-700 flex justify-between">
                                    <span>{{ $quote->quote_number }}</span>
                                    <span class="text-gray-500">${{ number_format($quote->total, 2) }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">{{ __('No quotes yet.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Payments') }} ({{ $client->payments->count() }})</h3>
                        <ul class="space-y-2">
                            @forelse($client->payments as $payment)
                                <li class="text-sm text-gray-700 flex justify-between">
                                    <span>{{ $payment->payment_reference }}</span>
                                    <span class="text-gray-500">${{ number_format($payment->amount, 2) }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">{{ __('No payments yet.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
