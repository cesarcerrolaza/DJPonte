@props(['disabled' => false, 'class' => ''])

<button {{ $attributes->merge([
    'class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 $class",
    'disabled' => $disabled,
]) }}>
    {{ $slot }}
</button>
