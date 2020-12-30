<?php

/**
 * Provides some of the default services used by most web apps
 *
 * @author C Varley <clvarley>
 */

return [

    // HTTP services
    Swiftly\Http\Server\RequestFactory::class => [
        'singleton' => true
    ],
    Swiftly\Http\Server\Request::class => [
        'singleton' => true,
        'handler'   => function ( Swiftly\Dependencies\Container $app ) {
            return $app->resolve( Swiftly\Http\Server\RequestFactory::class )->fromGlobals();
        }
    ],
    Swiftly\Http\Server\Response::class => [
        'singleton' => true
    ],

    // Template engine
    // TEMP: Allow config in future
    Swiftly\Template\TemplateInterface::class => Swiftly\Template\Php::class,

    // Route parser
    // TEMP: Allow config in future
    Swiftly\Routing\ParserInterface::class => Swiftly\Routing\Parser\JsonParser::class
];
