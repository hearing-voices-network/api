@extends('admin.layout')

@section('content')
  <form action="{{ route('auth.admin.login.code') }}" method="POST">
    @csrf

    <h2>Login</h2>

    <p>
      We have just sent you an authentication code to your phone.
      <br>
      You should have received it within the next 2 minutes.
    </p>

    <div>
      <label for="cpde">Confirmation code</label>

      <input type="number" id="code" name="code" minlength="5" maxlength="5">

      @if ($errors->has('code'))
        <p>
          <strong>{{ $errors->first('code') }}</strong>
        </p>
      @endif
    </div>


    <button type="submit">Login</button>
  </form>
@endsection
