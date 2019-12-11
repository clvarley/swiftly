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
     * @var \mysqli_result $results Query results
     */
    private $results = null;

    /**
     * Opens a connection to the MySQL database
     *
     * @return bool Connection opened
     */
    public function open() : bool
    {
        $this->handle = new \mysqli();

        $status = true;

        // Check for connection error
        if ( $this->handle->connect_errno !== 0 ) {
            $status = false;
        }

        return $status;
    }

    /**
     * Executes the given query
     *
     * @param string $query   SQL query
     * @return bool           Produced result
     */
    public function query( string $query ) : bool
    {
        $result = $this->handle->query( $query );

        if ( $response === false ) {

            $status = false;

        } else {

            $status = true;

            // Free memory
            if ( !is_null( $this->results ) ) {
                $this->results->free();
            }

            // Store results object
            if ( is_object( $response ) ) {
                $this->results = $result;
            } else {
                $this->results = null;
            }
        }

        return $status;
    }

    /**
     * Returns a single result from the last query
     *
     * @return array Query result
     */
    public function getResult() : array
    {
        return ( is_null( $this->results ) ? [] : $this->results->fetch_array( MYSQLI_ASSOC ) );
    }

    /**
     * Returns all results from the last query
     *
     * @return array Query results
     */
    public function getResults() : array
    {
        return ( is_null( $this->results ) ? [] : $this->results->fetch_all( MYSQLI_ASSOC ) );
    }

    /**
     * Get the auto incremented ID of the last INSERT operation
     *
     * @return int Row ID
     */
    public function getLastId() : int
    {
        return $this->handle->insert_id;
    }

    /**
     * Closes the connection to the MySQL database
     */
    public function close() : void
    {
        // Free any stray result object
        if ( !is_null( $this->results ) ) {
            $this->results->free();
        }

        $this->handle->close();

        return;
    }
}