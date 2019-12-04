<?php

namespace Swiftly\Database\Adapters;

use \Swiftly\Database\AdapterInterface;

/**
 * Driver for MySQL databases
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Mysql Implements AdapterInterface
{

    /**
     * Handle to the MySQL DB
     *
     * @var \mysqli $handle DB handle
     */
    private $handle = null;

    /**
     * Results of last query
     *
     * @var array $results Query results
     */
    private $results = [];

    /**
     * Opens a connection to the MySQL database
     *
     * @return bool Connection opened
     */
    public function open() : bool
    {
        $this->handle = new mysqli();

        return true;
    }

    /**
     * Executes the given query
     *
     * @param string $query   SQL query
     * @return bool           Produced result
     */
    public function query( string $query ) : bool
    {
        return true;
    }

    /**
     * Returns a single result from the last query
     *
     * @return array Query result
     */
    public function getResult() : array
    {
        return [];
    }

    /**
     * Returns all results from the last query
     *
     * @return array Query results
     */
    public function getResults() : array
    {
        return [];
    }

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return 0;
    }

    /**
     * Closes the connection to the MySQL database
     */
    public function close() : void
    {
        return;
    }
}