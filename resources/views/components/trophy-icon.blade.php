@php
    $colors = ['#FFD700', '#C0C0C0', '#CD7F32'];
    $color = $colors[$index] ?? null;
@endphp

@if($color)
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 flex-shrink-0" viewBox="0 0 24 24">
        <path fill="{{ $color }}" d="M12 2l1.5 4.5h5l-4 3.5 1.5 4.5-4-3.5-4 3.5 1.5-4.5-4-3.5h5z" />
        <path fill="{{ $color }}" d="M6.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
        <path fill="{{ $color }}" d="M19.5 8h-2c-.83 0-1.5.67-1.5 1.5v4c0 .83.67 1.5 1.5 1.5h2c.83 0 1.5-.67 1.5-1.5v-4c0-.83-.67-1.5-1.5-1.5z" />
        <rect fill="{{ $color }}" x="9" y="14" width="6" height="7" rx="1" />
    </svg>
@endif
