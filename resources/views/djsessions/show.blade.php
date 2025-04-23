@extends('layouts/app')

@section('content')
    @livewire('djsession-manager', [
        'djsession' => $djsession
    ])
@endsection