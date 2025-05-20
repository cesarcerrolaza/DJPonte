<div class="mt-8 pt-6 border-t border-gray-200">
    <h3 class="text-lg font-semibold mb-4">Top 3 Donantes de la Sesión</h3>
    
    @if(count($topDonors) > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($topDonors as $index => $donor)
                <li class="py-3 flex items-center">
                    @if($index <= 2)
                        <x-trophy-icon :index="$index" />
                    @else
                        <span class="ml-1 w-5 mr-2 inline-block text-yellow-400 font-semibold">$</span>
                    @endif
                    <div>
                        <p class="font-medium">{{ $donor['user'] }}</p>
                        <p class="text-sm text-yellow-500">{{ $donor['amount']/100 }} €</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 text-center py-4">No hay propinas en la sesión todavía.</p>
    @endif
</div>