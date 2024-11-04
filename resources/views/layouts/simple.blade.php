<!doctype html>
<html lang="{{ config('app.locale') }}" class="remember-theme">

<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

  <title>{{ config('app.name') }}</title>

  <meta name="description" content="OneUI - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave">
  <meta name="author" content="pixelcave">
  <meta name="robots" content="index, follow">


  <link rel="shortcut icon" href="{{ asset('media/favicons/favicon.png') }}">
  <link rel="icon" sizes="192x192" type="image/png" href="{{ asset('media/favicons/favicon-192x192.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('media/favicons/apple-touch-icon-180x180.png') }}">
  @yield('css')
  @vite(['resources/sass/main.scss', 'resources/js/oneui/app.js'])
  <script src="{{ asset('js/lib/jquery.min.js') }}"></script>
  <script src="{{ asset('js/setTheme.js') }}"></script>
  @yield('js')
</head>

<body>

  <div id="page-container">
    <main id="main-container">
      @yield('content')
    </main>
  </div>

 @stack('after_body')
</body>

</html>
