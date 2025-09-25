<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-gradient-custom {
            background: linear-gradient(135deg, rgb(67, 108, 117),rgb(50, 129, 145));
        }
    </style>
</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-gradient-custom text-white relative">
        
        @if (session('error'))
            <div class="text-red-500 text-center absolute top-10 w-full">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-2xl w-full p-8 rounded-lg shadow-lg bg-white text-gray-900 relative z-10">
            
            <div class="text-center text-2xl font-bold text-gray-900 mb-6">
                PCR.AC.ID
            </div>

            <div class="flex justify-center mt-4">
                <a href="{{ route('login.google', ['provider' => 'google']) }}" class="flex items-center justify-center 
                        bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-full shadow-lg transition duration-300 transform hover:scale-105">
                    Login with Google
                </a>
            </div>
        </div>
    </div>
</body>

</html>