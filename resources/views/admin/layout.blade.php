@extends('layout')

@section('html-class', 'govuk-template')
@section('body-class', 'govuk-template__body js-enabled')

@push('css')
  <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
@endpush

@push('js')
  <script src="{{ mix('/js/admin.js') }}"></script>
@endpush

@section('body')
  <div>
    @include('admin.partials.skip-link')
    @include('admin.partials.header', [
      'serviceName' => config('app.name'),
    ])

    <div class="govuk-width-container">
      <main
        id="main-content"
        class="govuk-main-wrapper govuk-main-wrapper--auto-spacing"
        role="main"
      >
        @yield('content')
      </main>
    </div>

    @include('admin.partials.footer', [
      'meta' => [
        'visuallyHiddenTitle' => 'Support links',
        'items' => [],
      ],
    ])
  </div>
@endsection
