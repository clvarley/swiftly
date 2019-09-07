<?php

$start = microtime( true );

/**
 * Swiftly | A Simple PHP Framework
 *
 * Swiftly is a simple MVC framework that developed out of a learning project
 * in the summer of 2019.
 *
 * More details about it's development, history and use can be found in the
 * readme file.
 *
 * @license MIT License
 * @author C Varley <cvarley@highorbit.co.uk>
 * @version 1.0.0 2019-08-11
 */


// Get global definitions
require_once '../definitions.php';


// Make sure we are running a compatable PHP version
if ( !defined('SWIFTLY_MIN_PHP') || version_compare( PHP_VERSION, SWIFTLY_MIN_PHP ) < 0 ) {
  exit( 'Swiftly requires PHP version ' . SWIFTLY_MIN_PHP . ' or above to run!' );
}


// Load utility functions & autoloader
require_once APP_SWIFTLY . 'utilities/functions.php';
require_once APP_SWIFTLY . 'utilities/Autoloader.php';


// Get the autoloader
$autoloader = new Autoloader();
$autoloader->addPrefix('*', APP_APP);
$autoloader->addPrefix('Swiftly', APP_SWIFTLY);


// Load the config
$config = Swiftly\Config\Config::fromJson( APP_CONFIG . 'app.json' );


// Set the encoding
if ( $config->hasValue('encoding') ) {
  mb_internal_encoding( $config->getValue('encoding') );
  mb_http_output( $config->getValue('encoding') );
}


// Are we in development mode?
switch ( (string)$config->getValue('environemt') )
{
  case 'development':
  case 'dev':
    $error_level = E_ERROR | E_WARNING | E_PARSE | E_NOTICE;
  break;

  default:
    $error_level = 0;
  break;
}


// Warning levels
if ( $config->hasValue('warnings') && (bool)$config->getValue('warnings') ) {
  $error_level = $error_level | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE;
}


// Set error level
error_reporting( $error_level );


// Start!
( new Swiftly\Application\Web( $config ) )->start();

$end = microtime( true );

echo '<pre>';
echo 'Time Started: ' . $start . PHP_EOL;
echo 'Time Ended: ' . $end . PHP_EOL;
echo 'Time Taken: ' . ( $end - $start ) . 's' . PHP_EOL;
echo 'Memory Used: ' . memory_get_peak_usage();
echo '</pre>';
