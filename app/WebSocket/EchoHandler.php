<?php

namespace App\WebSocket;

use HuangYi\Shadowfax\Contracts\WebSocket\Connection;
use HuangYi\Shadowfax\Contracts\WebSocket\Handler;
use HuangYi\Shadowfax\Contracts\WebSocket\Message;
use Illuminate\Http\Request;
use SplObjectStorage;

class EchoHandler implements Handler
{
    /**
     * The connections.
     *
     * @var \SplObjectStorage
     */
    protected $connections;

    /**
     * Create a new EchoHandler instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connections = new SplObjectStorage;
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
        $this->connections->attach($connection);

        $connection->send('Echo Server connected!');
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
        $this->connections->rewind();

        while ($this->connections->valid()) {
            $this->connections->current()->send($message->getData());
            $this->connections->next();
        }
    }

    /**
     * Handler for close event.
     *
     * @param  \HuangYi\Shadowfax\Contracts\WebSocket\Connection  $connection
     * @return mixed
     */
    public function onClose(Connection $connection)
    {
        $this->connections->detach($connection);
    }
}
