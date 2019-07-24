@extends('layout')

@push('css')
  <link rel="stylesheet" href="{{ mix('/css/docs.css') }}">
@endpush

@push('js')
  <script src="{{ mix('/js/docs.js') }}"></script>
@endpush

@section('body')
  <div id="swagger-ui"></div>
@endsection
