 @extends('layouts.app')

@section('content')
<div class="p-12 mx-auto max-w-sm">
  <h1 class="text-3xl">Reset Password</h1>

  @if (session('status'))
    <div class="text-green-700">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    <div class="my-4">
      <label for="email">E-Mail Address</label>

      <div class="control">
        <input id="email" type="email" class="block w-full border-2 border-gray-300 p-2{{ $errors->has('email') ? ' has-error' : '' }}" name="email" value="{{ old('email') }}" required>

        @if ($errors->has('email'))
          <span class="text-red-700">{{ $errors->first('email') }}</span>
        @endif
      </div>
    </div>

    <div class="my-4">
      <button type="submit" class="button px-5">
        Send Password Reset Link
      </button>
    </div>
  </form>
</div>
@endsection
