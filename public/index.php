<?php

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
 * @author C Varley <clvarley>
 * @version 1.0.0 2019-08-11
 */


// Get global definitions
require_once dirname( __DIR__ ) . '/definitions.php';


// Make sure we are running a compatable PHP version
if ( defined( 'SWIFTLY_MIN_PHP' ) && version_compare( PHP_VERSION, SWIFTLY_MIN_PHP ) < 0 ) {
    exit( 'Swiftly requires PHP version ' . SWIFTLY_MIN_PHP . ' or above to run!' );
}


// Load utility functions & autoloader
require_once APP_SWIFTLY . 'utilities/functions.php';
require_once APP_SWIFTLY . 'utilities/polyfills.php';
require_once APP_SWIFTLY . 'utilities/Autoloader.php';


// Get the autoloader
$autoloader = new Autoloader();
$autoloader->addPrefix( '*', APP_CONTROLLER );
$autoloader->addPrefix( 'Swiftly', APP_SWIFTLY );


// Load the config
$config = Swiftly\Config\Config::fromJson( APP_CONFIG . 'app.json' );


// Set the encoding
if ( $config->has( 'encoding' ) ) {
    mb_internal_encoding( $config->get( 'encoding' ) );
    mb_http_output( $config->get( 'encoding' ) );
}


// Are we in development mode?
switch ( (string)$config->get( 'environment' ) )
{
    case 'development':
    case 'dev':
        $error_level = E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_DEPRECATED | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING;
    break;

    default:
        $error_level = 0;
    break;
}


// Does the developer want to see E_STRICT errors?
if ( (bool)$config->get( 'strict', false ) ) {
    $error_level = $error_level | E_STRICT;
}


// Display developer defined errors & warnings?
if ( (bool)$config->get( 'warnings', false ) ) {
    $error_level = $error_level | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_USER_DEPRECATED;
}


// Set error level
error_reporting( $error_level );


// Start!
( new Swiftly\Application\Web( $config ) )->start();
