<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('enso-analytics::head')
    @include('enso-meta::display')
    <link href="https://fonts.googleapis.com/css2?family=Miriam+Libre:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  </head>
  <body class="flex">
    @include('enso-analytics::body')
    <div id="app" class="flex flex-grow min-h-screen flex-col">
      @include('parts.nav')
      <div class="flex-grow flex-shrink-0">
        @yield('content')
      </div>
      @include('parts.footer')
    </div>
    @includeWhen(config('enso.dev.show_responsive_helper'), 'enso-dev::responsive-helper')
    <script src="{{ mix('js/app.js') }}"></script>
  </body>
</html>
