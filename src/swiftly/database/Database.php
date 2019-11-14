<?php

namespace Swiftly\Database\Database;

use \Swiftly\Database\AdapterInterface;

/**
 * A database wrapper implementing the adapter pattern
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Database
{

    /**
     * @var AdapterInterface $adapter The DB driver
     */
    private $adapter = null;

    /**
     * @var bool $connected Is connection to DB open
     */
    private $connected = false;

    /**
     * Create a wrapper around the given adapter
     *
     * @param AdapterInterface $adapter The database adapter to use
     */
    public function __construct( AdapterInterface $adapter )
    {
        $this->adapter = $adapter;
    }

    /**
     * Makes sure we properly close the connection to the database
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Attempts to open a connection to the database
     *
     * @return bool Connection made successfully?
     */
    public function open() : bool
    {
        if ( !$this->connected ) {

            $this->connected = $this->adapter->open();

        }

        return $this->connected;
    }

    /**
     * Close the connection to the database
     */
    public function close() : void
    {
        if ( $this->connected ) {

            $this->adapter->close();
            $this->connected = false;

        }

        return;
    }

    /**
     * Executes the given query
     *
     * @param string $query Query
     */
    public function query( string $query ) : void
    {
        if ( $this->connected ) {

            $this->adapter->query( $query );

        }

        return;
    }

    /**
     * Executes the given query and returns the first result
     *
     * @param string $query Query
     * @return array        Query result
     */
    public function queryResult( string $query ) : array
    {
        $result = [];

        if ( $this->connected && $this->adapter->query( $query ) ) {

            $result = $this->adapter->getResult();

        }

        return $result;
    }

    /**
     * Executes the given query and returns all the results
     *
     * @param string $query Query
     * @return array        Query results
     */
    public function queryResults( string $query ) : array
    {
        $results = [];

        if ( $this->connected && $this->adapter->query( $query ) ) {

            $results = $this->adapter->getResults();

        }

        return $results;
    }

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return ( $this->connected ? $this->adapter->getLastId() : 0 );
    }

}