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

        @if($products->isEmpty())
            <p class="text-sm text-gray-500">
                {{ __('No products available yet.') }}
                <a href="{{ route('products.create') }}" class="text-blue-600 hover:text-blue-900">{{ __('Create one first') }}</a>.
            </p>
        @else
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

            <script>
                document.getElementById('product_id')?.addEventListener('change', function () {
                    const priceInput = document.getElementById('price');
                    const selected = this.options[this.selectedIndex];
                    if (priceInput && !priceInput.value && selected.dataset.price) {
                        priceInput.value = selected.dataset.price;
                    }
                });
            </script>
        @endif
    </div>
</div>
