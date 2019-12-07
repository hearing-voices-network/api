@extends('admin.layout')

@section('content')
  <div class="govuk-width-container">
    <main
      id="main-content"
      class="govuk-main-wrapper govuk-main-wrapper--auto-spacing"
      role="main"
    >
      <div class="govuk-grid-row">
        <div class="govuk-grid-column-two-thirds">
          <form action="{{ route('auth.admin.password.update') }}" method="POST">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

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

            <div class="govuk-form-group {{ $errors->has('password') ? 'govuk-form-group--error' : null }}">
              <label class="govuk-label" for="password">Password</label>

              @if($errors->has('password'))
                <span id="event-name-error" class="govuk-error-message">
                  <span class="govuk-visually-hidden">Error:</span> {{ $errors->first('password') }}
                </span>
              @endif

              <input
                class="govuk-input"
                type="password"
                id="password"
                name="password"
              >
            </div>

            <div class="govuk-form-group {{ $errors->has('password_confirmation') ? 'govuk-form-group--error' : null }}">
              <label class="govuk-label" for="password_confirmation">
                Confirm password
              </label>

              @if($errors->has('password_confirmation'))
                <span id="event-name-error" class="govuk-error-message">
                  <span class="govuk-visually-hidden">Error:</span> {{ $errors->first('password_confirmation') }}
                </span>
              @endif

              <input
                class="govuk-input"
                type="password"
                id="password_confirmation"
                name="password_confirmation"
              >
            </div>

            <button class="govuk-button" type="submit">Reset password</button>
          </form>
        </div>
      </div>
    </main>
  </div>
@endsection
