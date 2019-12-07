@extends('admin.layout')

@section('content')
  <h2 class="govuk-heading-l">{{ config('app.name') }} API</h2>

  <ul class="govuk-list">
    <li>
      <a
        class="govuk-link govuk-link--no-visited-state"
        href="{{ route('auth.admin.login')}}"
      >
        Admin Login
      </a>
    </li>

    <li>
      <a
        class="govuk-link govuk-link--no-visited-state"
        href="{{ route('auth.end-user.login')}}"
      >
        End User Login
      </a>
    </li>

    <li>
      <a
        class="govuk-link govuk-link--no-visited-state"
        href="{{ route('docs.index') }}"
      >
        API Docs
      </a>
    </li>
  </ul>
@endsection
