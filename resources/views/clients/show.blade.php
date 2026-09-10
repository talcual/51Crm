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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Purchased Products') }} ({{ $client->clientProducts->count() }})</h3>

                    <div class="overflow-x-auto mb-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Product') }}</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Description') }}</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Price') }}</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Purchase Date') }}</th>
                                    <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($client->clientProducts as $clientProduct)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $clientProduct->product->name ?? __('N/A') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $clientProduct->description ?? __('N/A') }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">${{ number_format($clientProduct->price, 2) }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-500">{{ $clientProduct->purchase_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <form action="{{ route('clients.products.destroy', [$client, $clientProduct]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('{{ __('Are you sure you want to remove this product?') }}')">{{ __('Remove') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">{{ __('No products purchased yet.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <form action="{{ route('clients.products.store', $client) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end border-t border-gray-200 pt-4">
                        @csrf
                        <div>
                            <x-input-label for="product_id" :value="__('Product')" />
                            <select id="product_id" name="product_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">{{ __('Select a product') }}</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" placeholder="dominio.com" />
                        </div>
                        <div>
                            <x-input-label for="price" :value="__('Price ($)')" />
                            <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="purchase_date" :value="__('Purchase Date')" />
                            <x-text-input id="purchase_date" name="purchase_date" type="date" class="mt-1 block w-full" :value="now()->format('Y-m-d')" required />
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <x-primary-button>{{ __('Add Product') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                document.getElementById('product_id')?.addEventListener('change', function () {
                    const priceInput = document.getElementById('price');
                    const selected = this.options[this.selectedIndex];
                    if (priceInput && !priceInput.value && selected.dataset.price) {
                        priceInput.value = selected.dataset.price;
                    }
                });
            </script>

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
