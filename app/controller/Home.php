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

      // Get all the files in this Swiftly install
      $swiftly_files = Directory::getFilesRecursive( APP_BASE );

      // Filter out the .git folder
      $swiftly_files = array_filter( $swiftly_files, function( $file ) {
          return strpos( $file->getPath(), '/.git/' ) === false;
      });

      // Output the response
      return $this->setOutput($this->render('home', [
          'title'   => 'Swiftly | A Simple Framework',
          'message' => 'Thanks for installing Swiftly!',
          'files'   => $swiftly_files
      ]));

    }
}
