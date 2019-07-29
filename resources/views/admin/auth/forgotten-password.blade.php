@extends('admin.layout')

@section('content')
  <form action="{{ route('auth.admin.password.email') }}" method="POST">
    @csrf

    @if (session('status'))
      <h2>{{ session('status') }}</h2>
    @endif

    <h2>Reset password</h2>

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

    <button type="submit">Send password reset link</button>
  </form>
@endsection
