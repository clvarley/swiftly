<?php

namespace Swiftly\Http\Server;

use \Swiftly\Http\{ Headers, Parameters};

/**
 * Represents a HTTP request received by the server (incoming)
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Request
{

  /**
   * @var Headers $headers HTTP header collection
   */
  public $headers = null;

  /**
   * @var string $method HTTP method
   */
  private $method = 'GET';

  /**
   * @var string $url The URL of this request
   */
  private $url = '';

  /**
   * @var Parameters $post The POST data send with this request
   */
  public $post = null;

  /**
   * @var Parameters $query The query (GET) params sent with this request
   */
  public $query = null;

  /**
   * @var string $body The request body/payload
   */
  private $body = '';

  /**
   * @var array HTTP_METHODS Allowed HTTP methods
   */
  public const HTTP_METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'UPDATE', 'OPTIONS', 'TRACE'];

  /**
   * Construct a new request
   *
   * @param string $method  HTTP method
   * @param string $url     Request URL
   * @param array $headers  HTTP headers
   * @param array $get      Query (GET) params
   * @param array $post     POST data
   * @param string $body    Request body
   */
  public function __construct( string $method = 'GET', string $url = '', array $headers = [], array $get = [], array $post = [], string $body = '' )
  {
    $this->setMethod( $method );

    $this->url = $url;
    $this->headers = new Headers( $headers );
    $this->query = new Parameters( $get );
    $this->post = new Parameters( $post );
    $this->body = $body;
  }

  /**
   * Set the HTTP method of this request
   *
   * @param string $method HTTP method
   */
  protected function setMethod( string $method ) : void
  {
    if ( in_array( mb_strtoupper($method), self::HTTP_METHODS ) ) {
      $this->method = mb_strtoupper( $method );
    } else {
      $this->method = 'GET';
    }

    return;
  }

  /**
   * Set the URL of this request
   *
   * @param string $url URL
   */
  protected function setUrl( string $url ) : void
  {
    $this->url = $url;

    return;
  }

  /**
   * Set the HTTP headers of this request
   *
   * @param array $headers HTTP headers
   */
  protected function setHeaders( array $headers ) : void
  {
    foreach ( $headers as $header_name => $header_value ) {
      $this->headers->addHeader($header_name, $header_value);
    }

    return;
  }

  /**
   * Set the GET parameters of this request
   *
   * @deprecated
   *
   * @param array $query GET params
   */
  protected function setQuery( array $query ) : void
  {
    return;
  }

  /**
   * Set the POST parameters of this request
   *
   * @deprecated
   *
   * @param array $post POST params
   */
  protected function setPost( array $post ) : void
  {
    return;
  }

  /**
   * Set the body/payload of this request
   *
   * @deprecated
   *
   * @param string $body Body
   */
  protected function setBody( string $body ) : void
  {
    return;
  }

  /**
   * Get the url for this request
   *
   *@return string Requested url
   */
  public function getUrl() : string
  {
    return $this->url;
  }

  /**
   * Create a new request object from PHP/Server globals
   *
   * @static
   *
   * @return Request Http request
   */
  public static function fromGlobals() : Request
  {
    $request = new Request(
      $_SERVER['REQUEST_METHOD'] ?? 'GET',
      $_SERVER['REQUEST_URI'] ?? '/',
      [],
      $_GET,
      $_POST,
      ''
    );

    foreach ( $_SERVER as $name => $value ) {
      if ( mb_strpos($name, 'HTTP_') === 0 ) {
        $request->headers->addHeader( mb_substr($name, 5), $value );
      }
    }

    return $request;
  }

}
