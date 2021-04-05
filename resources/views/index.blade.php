<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Prod -->
    {{-- <script type="text/javascript" src="https://js.squareup.com/v2/paymentform">
    </script> --}}
    <!-- Test -->
    {{-- <script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform">
    </script> --}}


    <!-- CSRF Token -->
    <script>
        window.csrfToken = '{{ csrf_token() }}'
    </script>
    <title>Multilevel Company Demo</title>



</head>

<body>
    <div id="app"></div>
    <script>
        var data = '{!! json_encode($data) !!}';
    </script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>