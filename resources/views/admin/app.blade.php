@extends('layout.main')

@section('content')
    <noscript>
        <strong>We're sorry but this page doesn't work properly without
            JavaScript enabled. Please enable it to continue.</strong>
    </noscript>

    <div id="admin-app">
    </div>

@endsection

@push('scripts')
    <script src="{{ mix('js/admin-app.js') }}"></script>
@endpush
