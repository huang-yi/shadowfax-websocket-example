<!doctype html>
<html lang="en" class="w-full h-full font-mono">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Echo Server</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="w-full h-full bg-gray-800">
<div id="app" class="h-full p-5 text-white" v-cloak>
    <div id="box">
        <div v-for="input in inputs" class="text-white font-bold">@{{ input }}</div>
    </div>

    <div class="flex text-green-400 font-bold">
        <span>&gt;</span>
        <input v-if="socket" id="input" type="text" class="border-none w-full bg-transparent outline-none px-5 font-bold">
    </div>
</div>
<script src="{{ asset('js/echo.js') }}"></script>
</body>
</html>
