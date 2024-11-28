<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'INTEGRACE - CAMPAIGN' }}</title>

</head>

<body class="bg-gray-50 dark:bg-gray-900">

{{ $slot }}

</body>

</html>

