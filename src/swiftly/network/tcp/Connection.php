<?php

namespace Swiftly\Network\Tcp;

/**
 * Basic abstraction wrapper for TCP connections
 *
 * @author C Varley <clvarley>
 */
Class Connection
{

    /**
     * @var Resource|null $socket Socket handle
     */
    private $socket = null;

    /**
     * @var bool $connected Connection status
     */
    private $connected = false;

    /**
     * Construct
     */
    public function __construct(  )
    {
        // TODO

        socket_create( AF_INET );

    }

}