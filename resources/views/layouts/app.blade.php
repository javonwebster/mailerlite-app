<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mailer Lite App</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-200">
    <nav class="p-6 bg-white flex justify-between mb-6">
        <ul class="flex items-center">
            <li>
                <a href="{{ route('enter-api-key') }}" class="p-3">Enter API Key</a>
            </li>
            <li>
                <a href="{{ route('subscriber-index') }}" class="p-3">Manage Subscribers</a>
            </li>
        </ul>
    </nav>
    @yield('content')
</body>
</html>
