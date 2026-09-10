<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Deals & Pipeline Management') }}
            </h2>
            <a href="{{ route('deals.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add New Deal') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex gap-4 overflow-x-auto pb-4">
                @forelse($stages as $stage)
                    <div class="bg-gray-50 rounded-lg shadow-sm flex-shrink-0 w-72">
                        <div class="p-4 border-b-4" style="border-color: {{ $stage->color }}">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800">{{ $stage->name }}</h3>
                                <span class="text-xs font-medium text-gray-500">{{ $stage->deals->count() }}</span>
                            </div>
                        </div>
                        <div class="p-3 space-y-3 min-h-[100px]">
                            @forelse($stage->deals as $deal)
                                <a href="{{ route('deals.show', $deal) }}" class="block bg-white rounded-md shadow p-3 hover:shadow-md transition">
                                    <p class="text-sm font-medium text-gray-900">{{ $deal->title }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $deal->client->name ?? __('N/A') }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-sm font-semibold text-green-700">${{ number_format($deal->value, 2) }}</span>
                                        <span class="text-xs text-gray-500">{{ $deal->probability }}%</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-xs text-gray-400 text-center py-4">{{ __('No deals') }}</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('No pipeline stages configured.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
