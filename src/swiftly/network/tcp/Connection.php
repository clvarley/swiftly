<?php

namespace Swiftly\Network\Tcp;

/**
 * Basic abstraction wrapper for TCP connections
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Connection
{

    /**
     * @var Resource|null $handle Stream handle
     */
    private $handle = null;

    /**
     * @var bool $connected Connection status
     */
    private $connected = false;

    /**
     * Construct
     */
    public function __construct()
    {
        // TODO
    }

}