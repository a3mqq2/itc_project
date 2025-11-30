@extends('layouts.app')

@section('title', __('messages.general_management'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('sections') }}">{{ __('messages.sections') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.general_management') }}</li>
@endsection

@section('content')

@endsection
