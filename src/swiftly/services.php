<?php

use Swiftly\Dependencies\Container;

/**
 * Provides some of the default services used by most web apps
 *
 * @author C Varley <clvarley>
 */
return [

    // HTTP services
    Swiftly\Http\Server\Request::class => [
        'singleton' => true,
        'handler'   => [ Swiftly\Http\Server\Request::class, 'fromGlobals' ]
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
