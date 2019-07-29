@extends('end-user.layout')

@section('content')
  <form action="{{ route('auth.end-user.password.update') }}" method="POST">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <h2>Reset password</h2>

    <div >
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

      @if ($errors->has('password'))
        <p>
          <strong>{{ $errors->first('password') }}</strong>
        </p>
      @endif
    </div>

    <div>
      <label for="password_confirmation">Confirm password</label>

      <div>
        <input type="password" id="password_confirmation" name="password_confirmation">
      </div>
    </div>

    <button type="submit">Reset password</button>
  </form>
@endsection
