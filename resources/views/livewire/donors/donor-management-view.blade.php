<div>
    <ul>
        @foreach($topDonors as $index => $donor)
            <li class="py-2 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 flex items-center">
                @if($index <= 2)
                    <x-trophy-icon :index="$index" />
                @else
                    <span class="ml-1 w-5 mr-2 inline-block text-yellow-400 font-semibold">$</span>
                @endif
                <div class="flex-1 flex justify-between items-center">
                    <span><strong class="text-yellow-600">{{ $donor['user'] }}</strong></span>
                    <span class="ml-2 px-2 py-1 text-s font-semibold rounded bg-yellow-100 text-yellow-800">{{ $donor['amount']/100 }} €</span>
                </div>
            </li>
        @endforeach
    </ul>
</div>