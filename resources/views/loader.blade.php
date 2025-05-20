<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargando...</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    @if ($type === 'tip')
        @livewire('tip-loader', [
            'tipId' => $id,
            'message' => 'Estamos confirmando tu pago... esto puede tardar unos segundos.',
            'secondaryMessage' => 'Estamos tardando más de lo normal en confirmar tu pago. Espera unos segundos.',
            'delay' => 10
        ])
    @else
        <x-loader 
            message="Estamos cargando la página... esto puede tardar unos segundos."
            secondaryMessage="Estamos tardando más de lo normal en cargar la página. Espera unos segundos."
            delay="10"
        />
    @endif
    @livewireScripts
</body>
</html>