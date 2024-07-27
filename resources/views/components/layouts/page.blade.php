@props(['title' => config('app.name')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head class="h-full bg-slate-50 dark:bg-slate-900">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <livewire:styles />
    </head>
    <body class="font-sans h-full antialiased text-slate-900 dark:text-slate-50 ">
        {{ $slot }}
        <livewire:scripts />
    </body>
</html>
