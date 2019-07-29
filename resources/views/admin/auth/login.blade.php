@extends('admin.layout')

@section('content')
  <form action="{{ route('auth.admin.login') }}" method="POST">
    @csrf

    <h2>Login</h2>

    <div>
      <label for="email">Email</label>

      <div>
        <input type="email" id="email" name="email" value="{{ old('email') }}">
      </div>

      @if ($errors->has('email'))
        <p>
          <strong>{{ $errors->first('email') }}</strong>
        </p>
      @endif
    </div>

    <div>
      <label for="password">Password</label>

      <div>
        <input type="password" id="password" name="password">
      </div>
    </div>

    <p class="body">
      <a href="{{ route('auth.admin.password.request') }}">Forgotten password?</a>
    </p>

    <button type="submit">
      @if (config('connecting_voices.otp_enabled'))
        Send code
      @else
        Confirm
      @endif
    </button>
  </form>
@endsection
