<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chatroom</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-200">
<div id="app" class="text-gray-900 relative" v-cloak>
    <div class="container mx-auto h-screen flex flex-col flex-no-wrap">
        <div class="text-center p-1 border-b border-gray-400 shadow-sm">
            <h1 class="font-bold text-2xl visible">CHATROOM</h1>
            <small>@{{ participant }}</small>
        </div>
        <div id="box" class="flex-1 w-full h-full overflow-y-auto scrolling-touch">
            <div class="py-3 px-2">
                <div v-for="message in messages" class="mb-5">
                    <div v-if="message.type === 'notification'" class="text-center">
                        <span class="bg-gray-300 text-gray-600 text-sm rounded p-1 break-all">@{{ message.content }}</span>
                    </div>
                    <div v-else-if="message.type === 'message' && message.user.id !== me.id">
                        <span class="mr-1 py-2 text-gray-700">@{{ message.user.name }}:</span>
                        <div class="flex flex-row">
                            <span class="inline-block bg-white rounded px-3 py-2 max-w-xl break-all">@{{ message.content }}</span>
                        </div>
                    </div>
                    <div v-else-if="message.type === 'message' && message.user.id === me.id">
                        <div class="flex flex-row-reverse">
                            <span class="inline-block bg-green-400 rounded px-3 py-2 max-w-xl break-all">@{{ message.content }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-16 p-2 border-t border-gray-400 shadow-sm">
            <input v-model="message" type="text" class="rounded h-full w-full p-1 outline-none border" placeholder="Type here..." @keypress="send">
        </div>
    </div>

    <div v-if="!me.id" class="absolute top-0 left-0 w-screen h-screen flex items-center justify-center">
        <div class="absolute bg-black opacity-75 w-full h-full"></div>
        <div class="relative bg-white rounded p-10">
            <h2 class="font-bold text-xl mb-10 text-center">What's your name?</h2>
            <div><input v-model="me.name" type="text" class="text-center outline-none border-b border-gray-600 text-lg w-56" @keypress="connect"></div>
        </div>
    </div>
</div>
<script src="{{ asset('js/chatroom.js') }}"></script>
</body>
</html>
