<div class="mt-3 space-y-1">
    @if(is_array($topDonors) && count($topDonors) > 0)
        @foreach($topDonors as $index => $donor)
            <div class="bg-white border-l-4 {{ $index < 3 ? ['border-yellow-400', 'border-gray-300', 'border-amber-600'][$index] : 'border-transparent' }} rounded shadow-sm p-2 flex items-center hover:bg-gray-50 transition-colors duration-200">
                @if ($index < 3)
                    @php
                        $textColors = [
                            0 => 'text-yellow-500',
                            1 => 'text-gray-400',
                            2 => 'text-amber-600',
                        ];
                    @endphp
                    <div class="flex-shrink-0 w-6 text-center">
                        <span class="{{ $textColors[$index] }} font-extrabold text-sm font-mono">#{{ $index + 1 }}</span>
                    </div>
                @else
                    <div class="flex-shrink-0 w-6 text-center">
                        <span class="text-gray-400 text-xs font-medium">{{ $index + 1 }}</span>
                    </div>
                @endif
                
                <div class="flex-grow ml-2 truncate">
                    <p class="text-sm text-yellow-900 font-medium font-sans truncate">{{ $donor['user'] }}</p>
                </div>
                
                <div class="flex-shrink-0">
                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded font-medium">
                        {{ $donor['amount']/100 }} €
                    </span>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-sm text-gray-600 font-medium">
        No hay donaciones en la djsession todavía.
        </p>
    @endif
</div>
