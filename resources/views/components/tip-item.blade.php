<div class="mt-6 pt-6 border-t border-gray-200">
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex justify-between items-start mb-2">
            <p class="font-bold text-lg">{{ $tipData['user_name'] }}</p>
            <p class="font-bold text-lg text-yellow-600">{{ $tipData['amount'] }}€</p>
        </div>

        @if(!empty($tipData['song_name']) || !empty($tipData['song_artist']))
            <div class="mb-2 bg-white p-3 rounded border border-yellow-100">
                <p class="font-medium">Canción solicitada:</p>
                <p>{{ $tipData['song_name'] }} - {{ $tipData['song_artist'] }}</p>
            </div>
        @endif

        @if(!empty($tipData['description']))
            <div class="text-gray-700 italic">
                "{{ $tipData['description'] }}"
            </div>
        @endif

        <p class="text-xs text-gray-500 mt-2">
            Recibido {{ \Carbon\Carbon::parse($tipData['updated_at'])->diffForHumans() }}
        </p>
    </div>
</div>


@props(['tipData'])