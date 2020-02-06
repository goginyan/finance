@extends('layout.auth')

@section('content')
    <noscript>
        <strong>We're sorry but this page doesn't work properly without
            JavaScript enabled. Please enable it to continue.</strong>
    </noscript>

    <div id="app">
    </div>

@endsection

@push('scripts')
    <script src="{{ asset(mix('js/login.js')) }}"></script>
@endpush
