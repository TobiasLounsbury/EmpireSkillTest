@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <div id="spa"></div>
@endsection

@push('after-scripts')
    <script src="{{ mix('js/spa.js') }}"></script>
    @if(auth()->user())
    <script>window.currentUserId = {{ auth()->user()->id }};</script>
    @endif
@endpush
