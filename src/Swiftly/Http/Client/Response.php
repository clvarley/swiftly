<?php

namespace Swiftly\Http\Client;

/**
 * Represents a HTTP response to a request by this client (incoming)
 *
 * @author C Varley <clvarley>
 */
Class Response
{

    /**
     * @var int $status_code HTTP status code
     */
    private $status_code = 0;

    /**
     * @var string $body Response body
     */
    private $body = '';

    /**
     * @var string $content_type Response content type
     */
    private $content_type = '';

    /**
     * @var array $headers Response headers
     */
    private $headers = [];

    /**
     * Create a response object
     *
     * @param int $status_code  [Optional] The returned HTTP status code
     * @param array $headers    [Optional] Returned HTTP headers
     * @param string $body      [Optional] Body of the response
     */
    public function __construct( int $status_code = 0, array $headers = [], string $body = '' )
    {
        $this->status_code = $status_code;
        $this->body = $body;
        $this->headers = $this->_parseHeaders( $headers );

        if ( isset( $this->headers['content-type'] ) ) {
            $this->content_type = $this->headers['content-type'][0];
        }
    }

    /**
     * Get the HTTP status code
     *
     * @return int Status code
     */
    public function getStatus() : int
    {
        return $this->status_code;
    }

    /**
     * Get the response body
     *
     * @return string Response body
     */
    public function getBody() : string
    {
        return $this->body;
    }

    /**
     * Get the content type
     *
     * @return string Content type
     */
    public function getType() : string
    {
        return $this->content_type;
    }

    /**
     * Get a header value
     *
     * @param  string $name The header name
     * @return string       Header value [Or empty]
     */
    public function getHeader( string $name ) : string
    {
        return ( \array_key_exists( $name, $this->headers ) ? $this->headers[$name][0] : '' );
    }

    /**
     * Get all values for a particular header
     *
     * @param  string $name The header name
     * @return array        Header values [Or empty]
     */
    public function getHeaders( string $name ) : array
    {
        return ( \array_key_exists( $name, $this->headers ) ? $this->headers[$name] : [] );
    }

    /**
     * Get all the HTTP headers for this response
     *
     * @return array Headers [Or empty]
     */
    public function getAllHeaders() : array
    {
        return $this->headers;
    }

    /**
     * Naive check to see if the response is valid
     *
     * @todo Refactor
     *
     * @return bool Valid
     */
    public function isValid() : bool
    {
        return $this->status_code !== 0;
    }

    /**
     * Parse array of headers into a format this object can use
     *
     * @param  array $headers Http headers
     * @return array          Parsed headers
     */
    private function _parseHeaders( array $headers ) : array
    {
        $return_headers = [];

        foreach ( $headers as $index => $value ) {

            if ( empty( $index ) ) continue;

            // $index = mb_strtolower($index);

            if ( \is_string( $index ) && \is_array( $headers ) ) {

                $return_headers[\mb_strtolower( $index )] = $value;

            } elseif ( \is_string( $index ) && \is_string( $value ) ) {

                $return_headers[\mb_strtolower( $index )][] = $value;

            } elseif ( \is_numeric( $index ) && \is_array( $value ) ) {

                $header_name = isset( $value[0] ) ? \trim( $value[0] ) : null;
                $header_value = isset( $value[1] ) ? \trim( $value[1] ) : '';

                if ( \is_string( $header_name ) && !empty( $header_name ) && \is_scalar( $header_value ) ) {
                    $return_headers[\mb_strtolower( $header_name )][] = $header_value;
                }
            }
        }

        return $return_headers;
    }
}
