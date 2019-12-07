@extends('admin.layout')

@section('content')
  <div class="govuk-grid-row">
    <div class="govuk-grid-column-two-thirds">
      <form action="{{ route('auth.admin.login.code') }}" method="POST">
        @csrf

        <h2 class="govuk-heading-l">Login</h2>

        <p class="govuk-body">
          We have just sent you an authentication code to your phone.
          <br>
          You should have received it within the next 2 minutes.
        </p>

        <div class="govuk-form-group {{ $errors->has('code') ? 'govuk-form-group--error' : null }}">
          <label class="govuk-label" for="code">Confirmation code</label>

          @if($errors->has('code'))
            <span id="event-name-error" class="govuk-error-message">
              <span class="govuk-visually-hidden">Error:</span> {{ $errors->first('code') }}
            </span>
          @endif

          <input
            class="govuk-input govuk-input--width-5"
            type="number"
            id="code"
            name="code"
            minlength="5"
            maxlength="5"
          >
        </div>

        <button class="govuk-button" type="submit">Login</button>
      </form>
    </div>
  </div>
@endsection
