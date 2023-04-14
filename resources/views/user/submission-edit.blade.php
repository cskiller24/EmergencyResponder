@extends('layouts.user-home')

@section('title')
    {{ $submission->name }} Edit | Emergency Responder
@endsection

@section('content')
    @livewire('submission-edit', compact('submission'))
@endsection
