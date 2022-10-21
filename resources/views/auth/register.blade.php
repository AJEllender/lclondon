@extends('layouts.app')

@section('content')
<div class="p-12 mx-auto max-w-sm">
  <h1 class="text-3xl">Register</h1>

  <form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <div class="my-4">
      <label for="name">Name</label>
      <input id="name" type="text" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('name') ? ' text-red-700' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

      @if ($errors->has('name'))
        <span class="help text-red-700">
          {{ $errors->first('name') }}
        </span>
      @endif
    </div>

    <div class="my-4">
      <label for="email">E-Mail Address</label>
      <input id="email" type="email" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('email') ? ' text-red-700' : '' }}" name="email" value="{{ old('email') }}" required>

      @if ($errors->has('email'))
        <span class="help text-red-700">
          {{ $errors->first('email') }}
        </span>
      @endif
    </div>

    <div class="my-4">
      <label for="password">Password</label>
      <input id="password" type="password" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('password') ? ' text-red-700' : '' }}" name="password" required>

      @if ($errors->has('password'))
        <span class="help text-red-700">
          {{ $errors->first('password') }}
        </span>
      @endif
    </div>

    <div class="my-4">
      <label for="password-confirm">Confirm Password</label>
      <input id="password-confirm" type="password" class="block w-full border-2 border-gray-300 p-2" name="password_confirmation" required>
    </div>

    <div class="my-4">
      <button type="submit" class="button is-primary">
        Register
      </button>
    </div>
  </form>
</div>
@endsection
