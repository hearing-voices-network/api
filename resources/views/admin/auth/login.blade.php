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
          <form action="{{ route('auth.admin.login') }}" method="POST">
            @csrf

            <h2 class="govuk-heading-l">Login</h2>

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

            <div class="govuk-form-group">
              <label class="govuk-label" for="password">Password</label>

              <input
                class="govuk-input"
                type="password"
                id="password"
                name="password"
              >
            </div>

            <div style="margin-bottom: 1rem;">
              <a
                class="govuk-link govuk-link--no-visited-state"
                href="{{ route('auth.admin.password.request') }}"
              >
                Forgotten password?
              </a>
            </div>

            <button class="govuk-button" type="submit">
              @if (config('connecting_voices.otp_enabled'))
                Send code
              @else
                Confirm
              @endif
            </button>
          </form>
        </div>
      </div>
    </main>
  </div>
@endsection
