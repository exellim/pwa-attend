<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - PRIMA KATROLINDO SEJATI</title>

    <!-- Styles / Scripts -->
    @laravelPWA
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100 w-full px-2">
    <div class="w-full max-w-lg text-center rounded-lg overflow-hidden shadow-lg bg-white p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">PRIMA KATROLINDO SEJATI</h1>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded-lg">
                <ul class="text-left">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="font-bold text-xl mb-4">LOGIN</div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf

            <!-- Email Input -->
            <div class="flex items-center border rounded-lg mb-4 px-4 py-2 bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-6 h-6 text-gray-500">
                    <path fill-rule="evenodd"
                        d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                        clip-rule="evenodd" />
                </svg>
                <input class="ml-3 bg-transparent w-full outline-none text-gray-700 py-2" type="email" name="email"
                    id="email" placeholder="Email" required autofocus>
            </div>

            <!-- Password Input -->
            <div class="flex items-center border rounded-lg mb-6 px-4 py-2 bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-6 h-6 text-gray-500">
                    <path fill-rule="evenodd"
                        d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                        clip-rule="evenodd" />
                </svg>
                <input class="ml-3 bg-transparent w-full outline-none text-gray-700 py-2" id="password" name="password"
                    type="password" placeholder="Password" required>
            </div>

            <!-- Sign In Button -->
            <div class="flex items-center justify-center">
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 w-full rounded-lg focus:outline-none focus:shadow-outline"
                    type="submit">
                    Sign In
                </button>
            </div>
        </form>

        <!-- Forgot Password -->
        <div class="mt-4">
            <a href="{{ route('password.request') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                Forgot Password?
            </a>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            &copy; 2024 PRIMA KATROLINDO SEJATI. All rights reserved.
        </p>
    </div>
</body>

</html>
