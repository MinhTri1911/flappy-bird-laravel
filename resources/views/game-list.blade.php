<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container mx-auto px-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($games as $game)
                                <div class="max-w-sm rounded overflow-hidden shadow-lg cursor-pointer" onclick="window.location='{{ route('game.play', ['id' => $game->id]) }}'">
                                    <img class="w-full" src="{{ asset('images/game-icon.png') }}" alt="{{ $game->name }}">
                                    <div class="px-6 py-4">
                                        <div class="font-bold text-xl mb-2">{{ $game->name }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        {!! $games->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
