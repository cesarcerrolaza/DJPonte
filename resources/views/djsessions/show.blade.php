@extends('layouts/app')

@section('content')
<div class="p-6">
    @livewire('djsession-manager', [
        'djsession' => $djsession
    ])
</div>
@endsection