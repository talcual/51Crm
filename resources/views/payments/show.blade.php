<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payment') }} {{ $payment->payment_reference }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Client') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $payment->client->name ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Related Deal') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $payment->deal->title ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Amount') }}</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($payment->amount, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst($payment->status)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Payment Method') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst(str_replace('_', ' ', $payment->payment_method))) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Payment Date') }}</dt>
                            <dd class="text-sm text-gray-900">{{ optional($payment->payment_date)->format('M d, Y') ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Transaction ID') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $payment->transaction_id ?? __('N/A') }}</dd>
                        </div>
                        @if($payment->notes)
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                                <dd class="text-sm text-gray-900">{{ $payment->notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Loyalty Points Earned') }} ({{ $payment->loyaltyPoints->count() }})</h3>
                    <ul class="space-y-2">
                        @forelse($payment->loyaltyPoints as $point)
                            <li class="text-sm text-gray-700 flex justify-between">
                                <span>{{ $point->reason }}</span>
                                <span class="text-gray-500">{{ $point->points }} {{ __('pts') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('No loyalty points recorded.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
