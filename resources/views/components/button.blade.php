@props(['disabled' => false])

<button {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-500 transition ease-in-out duration-150', 'disabled' => $disabled]) }}>
    {{ $slot }}
</button>
