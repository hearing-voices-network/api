@extends('layout')

@section('body')
    <h1>Passport</h1>

    <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

    <!-- Scope List -->
    @if (count($scopes) > 0)
        <div>
            <p><strong>This application will be able to:</strong></p>

            <ul>
                @foreach ($scopes as $scope)
                    <li>{{ $scope->description }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Authorize Button -->
    <form method="POST" action="{{ route('passport.authorizations.approve') }}">
        @csrf

        <input type="hidden" name="state" value="{{ $request->state }}">
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <button type="submit">Authorise</button>
    </form>

    <!-- Cancel Button -->
    <form method="POST" action="{{ route('passport.authorizations.deny') }}">
        @csrf
        @method('DELETE')

        <input type="hidden" name="state" value="{{ $request->state }}">
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <button>Cancel</button>
    </form>
@endsection
