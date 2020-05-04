<?php

namespace App\WebSocket;

use HuangYi\Shadowfax\Contracts\WebSocket\Connection;
use HuangYi\Shadowfax\Contracts\WebSocket\Handler;
use HuangYi\Shadowfax\Contracts\WebSocket\Message;
use Illuminate\Http\Request;
use SplObjectStorage;

class ChatroomHandler implements Handler
{
    /**
     * The user connection mappings.
     *
     * @var \SplObjectStorage
     */
    protected $users;

    /**
     * Create a new ChatroomHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->users = new SplObjectStorage();
    }

    /**
     * Handler for handshake event.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function onHandshake(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'between:1,12'],
        ]);
    }

    /**
     * Handler for open event.
     *
     * @param  \HuangYi\Shadowfax\Contracts\WebSocket\Connection  $connection
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function onOpen(Connection $connection, Request $request)
    {
        $this->users[$connection] = $user = [
            'id' => $connection->getId(),
            'name' => $request->name,
        ];

        $connection->send(['connected', $user]);

        $this->broadcast(['joined', [
            'user' => $user,
            'users_count' => $this->users->count(),
        ]]);
    }

    /**
     * Handler for message event.
     *
     * @param  \HuangYi\Shadowfax\Contracts\WebSocket\Connection  $connection
     * @param  \HuangYi\Shadowfax\Contracts\WebSocket\Message  $message
     * @return mixed
     */
    public function onMessage(Connection $connection, Message $message)
    {
        $this->broadcast(['message', [
            'user' => $this->users[$connection],
            'content' => $message->getData(),
        ]]);
    }

    /**
     * Handler for close event.
     *
     * @param  \HuangYi\Shadowfax\Contracts\WebSocket\Connection  $connection
     * @return mixed
     */
    public function onClose(Connection $connection)
    {
        $user = $this->users[$connection];

        unset($this->users[$connection]);

        $this->broadcast(['left', [
            'user' => $user,
            'users_count' => $this->users->count(),
        ]]);
    }

    /**
     * Broadcast the message to all users in the chatroom.
     *
     * @param  array  $message
     * @return void
     */
    protected function broadcast(array $message)
    {
        $this->users->rewind();

        while ($this->users->valid()) {
            $this->users->current()->send($message);
            $this->users->next();
        }

        $this->users->rewind();
    }
}
