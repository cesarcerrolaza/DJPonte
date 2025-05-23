@extends('layouts/app')

@section('content')
    @foreach($djsessions as $djsession)
        @include('components.djsession-card', [
            'djsession' => $djsession,
            'location' => $djsession->fullLocation(),
            'djName' => $djsession->dj->name,
            'djAvatar' => $djsession->dj->profile_photo_path,
        ])
        <br>
    @endforeach
@endsection