<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    {{ Html::style(url('css/plugins/bootstrap.min.css')) }}
    {{ Html::style('css/plugins/bootstrap-theme.min.css') }}
    @yield('ext_css')
    {{ Html::style(url('css/app.custom.css')) }}

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    @include('layouts.partials.top-nav')
    <div class="container">
        @include('flash::message')
        @yield('content')
        <br>
    </div>

    <!-- Scripts -->
    {{ Html::script(url('js/plugins/jquery.min.js')) }}
    {{ Html::script(url('js/plugins/bootstrap.min.js')) }}
    @stack('ext_js')
    <script>
    $('div.notifier').not('.alert-important').delay(5000).fadeOut(350);
    </script>
    @yield('script')
</body>
</html>
