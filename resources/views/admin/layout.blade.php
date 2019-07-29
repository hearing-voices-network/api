@extends('layout')

@push('css')
  <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
@endpush

@push('js')
  <script src="{{ mix('/js/admin.js') }}"></script>
@endpush

@section('body')
  <div>
    <h1>Admin</h1>

    <!-- Content -->
    @yield('content')
  </div>
@endsection
