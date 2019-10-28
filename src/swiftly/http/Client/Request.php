<?php

namespace Swiftly\Http\Client;

use Swiftly\Http\Client\Response;

/**
 * Represents a HTTP request sent by this client (outgoing)
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Request
{

    /**
     * @var string $url Request URL
     */
    private $url = '';

    /**
     * @var string $scheme Request protocol
     */
    private $protocol = 'http';

    /**
     * @var string $domain Request URL domain
     */
    private $domain = '';

    /**
     * @var string $path Request URL path
     */
    private $path = '/';

    /**
     * @var array $headers Request headers
     */
    private $headers = [];

    /**
     * @var string $body Request body
     */
    private $body = '';

    /**
     * @var Resource|null $handle Curl handle
     */
    private $handle = null;

    /**
     * @var Response|null $response Response
     */
    private $response = null;

    /**
     * @var array $response_headers CURL response headers
     * @deprecated Needs refactoring!
     */
    private $response_headers = [];

    /**
     * Construct a new request object
     *
     * @param string $url     URL
     * @param array  $headers [Optional] Request headers
     */
    public function __construct( string $url, array $headers = [] )
    {
        $this->url = $url;
        $this->headers = $headers;
    }

    /**
     * Destroys the object and closes the curl handle
     */
    public function __destruct()
    {
        if ( !is_null($this->handle) ) {
            curl_close( $this->handle );
        }
    }

    /**
     * Send a request
     *
     * @see Request::get() Alias
     *
     * @return Response|null Response object [Or null]
     */
    public function send() : ?Response
    {
        return $this->get();
    }

    /**
     * Send a get request
     *
     * @return Response|null Response object [Or null]
     */
    public function get() : ?Response
    {
        if ( !$this->prepare() ) {
            return null;
        }

        $response = curl_exec( $this->handle );

        if ( $response === false ) {
            return null;
        }

        $status_code = +(int)curl_getinfo( $this->handle, CURLINFO_HTTP_CODE );

        return ( new Response( $status_code, $this->response_headers, $response ) );
    }

    /**
     * Send a post request
     *
     * @return Response|null Response object [Or null]
     */
    public function post( string $body = '' ) : ?Response
    {
        if ( !$this->prepare() ) {
            return null;
        }

        if ( $body ) {
            $this->body = $body;
        }

        curl_setopt( $this->handle, CURLOPT_POST, true );
        curl_setopt( $this->handle, CURLOPT_POSTFIELDS, $this->body );
        curl_setopt( $this->handle, CURLOPT_HTTPHEADER, array_merge( $this->headers, [
            'Content-Length: ' . strlen($this->body)
        ]));

        $response = curl_exec( $this->handle );

        if ( $response === false ) {
            return null;
        }

        $status_code = +(int)curl_getinfo( $this->handle, CURLINFO_HTTP_CODE );

        // TODO: Check this is robust enough, dev will have to content type 

        return ( new Response( $status_code, $this->response_headers, $response ) );
    }

    /**
     * Send a put request
     *
     * @return Response|null Response object [Or null]
     */
    public function put() : ?Response
    {
        if ( !$this->prepare() ) {
            return null;
        }

        curl_setopt( $this->handle, CURLOPT_CUSTOMREQUEST, 'PUT' );

        // TODO:

        return null;
    }

    /**
     * Send a delete request
     *
     * @return Response|null Response object [Or null]
     */
    public function delete() : ?Response
    {
        if ( !$this->prepare() ) {
            return null;
        }

        curl_setopt( $this->handle, CURLOPT_CUSTOMREQUEST, 'DELETE' );

        // TODO:

        return null;
    }

    /**
     * Prepares the curl handle
     *
     * @return bool Curl handle is valid
     */
    protected function prepare() : bool
    {
        if ( is_null($this->handle) ) {

            $this->handle = curl_init();

            if ( $this->handle !== false ) {

                curl_setopt( $this->handle, CURLOPT_URL, $this->url );
                curl_setopt( $this->handle, CURLOPT_HEADER, false );
                curl_setopt( $this->handle, CURLOPT_RETURNTRANSFER, true );
                curl_setopt( $this->handle, CURLOPT_HEADERFUNCTION, [ $this, '_parseHeaders' ] );

                if ( !empty($this->headers) ) {

                    curl_setopt( $this->handle, CURLOPT_HTTPHEADER, $this->headers );

                }

            } else {

                return false;

            }
        }

        return true;
    }

    /**
     * Parses the HTTP headers supplied by CURL
     *
     * @see CURL_HEADERFUNCTION
     * @link https://www.php.net/manual/en/function.curl-setopt.php
     *
     * @param Resource|null $curl_handle    The curl handle
     * @param string $header                The header string
     * @return int                          The length of the headers
     */
    private function _parseHeaders( $curl_handle = null, string $header )
    {
        if ( $header ) {
            $this->response_headers[] = explode( ':', $header, 2 );
        }

        return strlen( $header );
    }
}
