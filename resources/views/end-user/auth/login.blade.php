@extends('end-user.layout')

@section('content')
  <form action="{{ route('auth.end-user.login') }}" method="POST">
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
      <a href="{{ route('auth.end-user.password.request') }}">Forgotten password?</a>
    </p>

    <button type="submit">Confirm</button>
  </form>
@endsection
