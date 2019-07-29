@extends('end-user.layout')

@section('content')
  <h2>Login</h2>

  @if (session('resent'))
    <p>A fresh verification link has been sent to your email address.</p>
  @endif

  Before proceeding, please check your email for a verification link. If you did
  not receive the email,
  <a href="{{ route('auth.end-user.verification.resend') }}">
    click here to request another
  </a>.
@endsection
