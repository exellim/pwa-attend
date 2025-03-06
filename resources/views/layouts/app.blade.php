<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @laravelPWA

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            border-radius: 10px;
            display: none;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(128, 128, 128, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(128, 128, 128, 0.1);
        }

        ::-webkit-scrollbar-button {
            display: none;
        }
    </style>
    @stack('style')

</head>

<body class="items-center justify-center h-full w-full bg-gray-100 py-10">
    @include('sweetalert::alert')

    <div class="w-full h-full overflow-y-auto pb-20">
        @yield('content')
    </div>

    @include('layouts.footer')

</body>

</html>
