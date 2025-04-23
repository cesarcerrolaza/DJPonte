<button {{ $attributes->merge(['disabled', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 transition ease-in-out duration-150', 'disabled' => $disabled]) }}>
    {{ $slot }}
</button>
