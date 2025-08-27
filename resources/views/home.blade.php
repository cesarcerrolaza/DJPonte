@extends('layouts/app')

@section('content')
    <div class="p-6 bg-gray-100">
        @livewire('djsession-manager', ['djsessionId' => 1])
    </div>
@endsection
