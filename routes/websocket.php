<?php

use App\WebSocket\ChatroomHandler;
use App\WebSocket\EchoHandler;
use HuangYi\Shadowfax\Facades\WebSocket;

WebSocket::listen('/echo', new EchoHandler);
WebSocket::listen('/chatroom', new ChatroomHandler);
