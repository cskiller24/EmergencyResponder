@extends('layouts.moderator-home')

@section('title')
    Edit {{ $responder->name }} | Emergency Responder
@endsection

@section('page-title')
    Responder
@endsection

@section('content')
    @livewire('responder-edit', compact('responder'))
@endsection
