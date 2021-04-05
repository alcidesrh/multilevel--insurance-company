<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:500&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>window.csrfToken = '{{ csrf_token() }}'</script>
    <title></title>



</head>

<body>
    <div id="app"></div>
    <script>
        var user = '{!! json_encode($user) !!}';
    </script>
    <script src="{{ mix('js/application.js') }}"></script>
</body>

</html>