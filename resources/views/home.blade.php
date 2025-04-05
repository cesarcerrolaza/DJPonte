@extends('layouts/app')

@section('content')

    @livewire('djsession-manager', ['djsessionId' => 1])
@endsection
