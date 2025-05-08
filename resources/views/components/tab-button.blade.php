@props([
    'tab',
    'color' => 'indigo',
])

<button
    {{ $attributes->merge([
        'type' => 'button',
        'class' => 'py-3 px-1 border-b-2 font-semibold transition-colors duration-300 flex items-center',
    ]) }}
    :class="{
        'border-{{ $color }}-500 text-{{ $color }}-600': activeTab === '{{ $tab }}',
        'border-transparent text-gray-500 hover:text-{{ $color }}-600 hover:border-{{ $color }}-500': activeTab !== '{{ $tab }}'
    }"
>
    {{ $slot }}
</button>

