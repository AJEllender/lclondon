@extends('layouts.app')

@section('content')
<div class="p-12 mx-auto max-w-sm">
  <h1 class="text-3xl">Reset Password</h1>

  <form method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="my-4">
      <label for="email">E-Mail Address</label>

      <input id="email" type="email" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('email') ? ' text-red-700' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>

      @if ($errors->has('email'))
        <span class="text-red-700">{{ $errors->first('email') }}</span>
      @endif
    </div>

    <div class="my-4">
      <label for="password">Password</label>

      <input id="password" type="password" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('password') ? ' text-red-700' : '' }}" name="password" required>

      @if ($errors->has('password'))
        <span class="text-red-700">{{ $errors->first('password') }}</span>
      @endif
    </div>

    <div class="my-4">
      <label for="password-confirm">Confirm Password</label>
      <input id="password-confirm" type="password" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('password_confirmation') ? ' text-red-700' : '' }}" name="password_confirmation" required>

      @if ($errors->has('password_confirmation'))
        <span class="text-red-700">{{ $errors->first('password_confirmation') }}</span>
      @endif
    </div>

    <div class="my-4">
      <button type="submit" class="button px-5">
        Reset Password
      </button>
    </div>
  </form>
</div>
@endsection
