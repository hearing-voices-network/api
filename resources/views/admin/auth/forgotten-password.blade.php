@extends('admin.layout')

@section('content')
  @if(session()->has('status'))
    <div class="govuk-panel govuk-panel--confirmation">
      <h1 class="govuk-panel__title">
        Check your email
      </h1>
      <div class="govuk-panel__body">
        {{ session('status') }}
      </div>
    </div>
  @endif

  <div class="govuk-grid-row">
    <div class="govuk-grid-column-two-thirds">
      <form action="{{ route('auth.admin.password.email') }}" method="POST">
        @csrf

        <h2 class="govuk-heading-l">Reset password</h2>

        <div class="govuk-form-group {{ $errors->has('email') ? 'govuk-form-group--error' : null }}">
          <label class="govuk-label" for="email">Email</label>

          @if($errors->has('email'))
            <span id="event-name-error" class="govuk-error-message">
              <span class="govuk-visually-hidden">Error:</span> {{ $errors->first('email') }}
            </span>
          @endif

          <input
            class="govuk-input"
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
          >
        </div>

        <button class="govuk-button" type="submit">
          Send password reset link
        </button>
      </form>
    </div>
  </div>
@endsection
