@extends('layouts.app')

@section('content')
<div class="p-12 mx-auto max-w-sm">
  <h1 class="text-3xl">Login</h1>

  <form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="my-4">
      <label for="email">E-Mail Address</label>

      <input id="email" type="email" class="block w-full border-2 border-gray-300 p-2 {{ $errors->has('email') ? ' text-red-700' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

      @if ($errors->has('email'))
        <span class="text-red-700">
          {{ $errors->first('email') }}
        </span>
      @endif
    </div>

    <div class="my-4">
      <label for="password">Password</label>

      <input id="password" type="password" class="block w-full border-2 border-gray-300 p-2 {{ $errors->has('password') ? ' text-red-700' : '' }}" name="password" required>

      @if ($errors->has('password'))
        <span class="text-red-700">
          {{ $errors->first('password') }}
        </span>
      @endif
    </div>

    <div class="my-4">
      <label class="text-sm">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        Remember Me
      </label>
    </div>

    <div class="my-4">
      <button type="submit" class="button py-2 px-4 mr-4">
        Login
      </button>

      <a class="text-sm" href="{{ route('password.request') }}">
        Forgot Your Password?
      </a>
    </div>
  </form>
</div>
@endsection
