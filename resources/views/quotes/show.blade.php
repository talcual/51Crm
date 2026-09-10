<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Quote') }} {{ $quote->quote_number }}
            </h2>
            <div class="flex gap-3">
                <form action="{{ route('quotes.pdf', $quote) }}" method="POST" target="_blank">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900">
                        {{ __('Download PDF') }}
                    </button>
                </form>
                <a href="{{ route('quotes.edit', $quote) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
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
                            <dd class="text-sm text-gray-900">{{ $quote->client->name ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Related Deal') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $quote->deal->title ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                            <dd class="text-sm text-gray-900">{{ __(ucfirst($quote->status)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Valid Until') }}</dt>
                            <dd class="text-sm text-gray-900">{{ optional($quote->valid_until)->format('M d, Y') ?? __('N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Created By') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $quote->createdBy->name ?? __('N/A') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Line Items') }}</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Qty') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Unit Price') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($quote->items as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900">{{ $item->description }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-500">${{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end mt-4">
                        <dl class="w-64 space-y-1">
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">{{ __('Subtotal') }}</dt>
                                <dd class="text-gray-900">${{ number_format($quote->subtotal, 2) }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">{{ __('Tax') }}</dt>
                                <dd class="text-gray-900">${{ number_format($quote->tax, 2) }}</dd>
                            </div>
                            <div class="flex justify-between text-sm">
                                <dt class="text-gray-500">{{ __('Discount') }}</dt>
                                <dd class="text-gray-900">-${{ number_format($quote->discount, 2) }}</dd>
                            </div>
                            <div class="flex justify-between text-base font-semibold border-t pt-1">
                                <dt>{{ __('Total') }}</dt>
                                <dd>${{ number_format($quote->total, 2) }}</dd>
                            </div>
                        </dl>
                    </div>

                    @if($quote->terms)
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Terms') }}</h4>
                            <p class="text-sm text-gray-900">{{ $quote->terms }}</p>
                        </div>
                    @endif

                    @if($quote->notes)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Notes') }}</h4>
                            <p class="text-sm text-gray-900">{{ $quote->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
