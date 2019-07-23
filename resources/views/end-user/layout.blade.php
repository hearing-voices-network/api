@extends('layout')

@push('css')
  <link rel="stylesheet" href="{{ mix('/css/end-user.css') }}">
@endpush

@push('js')
  <script src="{{ mix('/js/end-user.js') }}"></script>
@endpush

@section('body')
  <div>
    <h1>End User</h1>

    <!-- Content -->
    @yield('content')
  </div>
@endsection
