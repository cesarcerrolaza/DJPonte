@props([
    'timeout' => 20,
])
<div class="bg-gray-100 rounded-lg px-2 py-1 text-gray-600 flex items-center ml-4 mb-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div class="flex flex-col">
        <div class="flex items-center">
            <input 
                type="number" 
                id="song_request_timeout"
                name="song_request_timeout" 
                placeholder="nº"
                min="0"
                class="bg-transparent border-b border-gray-300 focus:border-gray-600 focus:outline-none w-[8ch] text-center"
                value="{{$timeout}}"
            >
            <select 
                id="timeout_unit" 
                name="timeout_unit" 
                class="ml-2 bg-transparent border-b border-gray-300 focus:border-gray-600 focus:outline-none"
            >
                <option value="seconds">segundos</option>
                <option value="minutes">minutos</option>
            </select>
        </div>
        <span class="text-xs text-gray-500 mt-1">Tiempo entre solicitudes</span>
    </div>
</div>