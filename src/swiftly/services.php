<?php

use Swiftly\Dependencies\Container;

/**
 * Provides some of the default services used by most web apps
 *
 * @author C Varley <clvarley>
 */
return [
    // Database service
    Swiftly\Database\Database::class => [
        'singleton' => true
    ],
    Swiftly\Database\AdapterInterface::class => [
        'private'   => true,
        'handler'   => function ( Container $services ) {

        }
    ],

    // HTTP response object
    Swiftly\Http\Server\Request::class => [
        'singleton' => true
    ],

    // Template engine
    Swiftly\Template\TemplateInterface::class => [
        // TEMP: Allow config of this
        'handler'   => function ( Container $services ) {
            return $services->resolve( Swiftly\Template\Php::class );
        }
    ]
];
