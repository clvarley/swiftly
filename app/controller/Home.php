<?php

use Swiftly\Base\Controller;
use Swiftly\Filesystem\Directory;

/**
 * The default controller that handles the homepage
 *
 * @author C Varley <clvarley>
 */
Class Home Extends Controller
{

    /**
     * Load the homepage template
     */
    public function index()
    {
        // Output a response
        return $this->setOutput($this->render('home', [
            'title'   => 'Swiftly',
            'message' => 'Thanks for installing Swiftly!'
        ]));
    }
}
