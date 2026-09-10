<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Deal Details') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('deals.edit', $deal) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('deals.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $deal->title }}</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Client') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $deal->client->name ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pipeline Stage') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $deal->pipelineStage->name ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Value') }}</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($deal->value, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Probability') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $deal->probability }}%</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst($deal->status)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Assigned To') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $deal->assignedUser->name ?? __('Unassigned') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Expected Close Date') }}</dt>
                            <dd class="text-sm text-gray-900">{{ optional($deal->expected_close_date)->format('M d, Y') ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Closed Date') }}</dt>
                            <dd class="text-sm text-gray-900">{{ optional($deal->closed_date)->format('M d, Y') ?? __('N/A') }}</dd>
                        </div>
                        @if($deal->description)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Description') }}</dt>
                                <dd class="text-sm text-gray-900">{{ $deal->description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Payments') }} ({{ $deal->payments->count() }})</h3>
                    <ul class="space-y-2">
                        @forelse($deal->payments as $payment)
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
</x-app-layout>
