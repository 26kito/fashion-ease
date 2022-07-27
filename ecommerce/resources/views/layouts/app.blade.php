<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>
  @include('layout.adminlte.stylesheet')
</head>
<body class="hold-transition login-page">
  @yield('content')

  @include('layout.adminlte.js')
</body>
</html>
