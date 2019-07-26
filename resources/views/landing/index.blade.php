@extends('layout')

@section('body')
  <ul>
    <li><a href="{{ route('auth.admin.login')}}">Admin Login</a></li>
    <li><a href="{{ route('auth.end-user.login')}}">End User Login</a></li>
    <li><a href="{{ route('docs.index') }}">API Docs</a></li>
  </ul>
@endsection
