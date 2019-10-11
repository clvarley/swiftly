<?php

use Swiftly\Base\Controller;
use Swiftly\Filesystem\Directory;

/**
 * The default controller that handles the homepage
 *
 * @author C Varley <cvarley@highorbit.co.uk>
 */
Class Home Extends Controller
{

  /**
   * Load the homepage template
   */
  public function index()
  {

      $dir = new Directory( '/var/www/html' );

      $this->setOutput($this->render('home', [
          'title'   => 'Swiftly | A Simple Framework',
          'message' => 'Thanks for installing Swiftly!',
          'files'   => Directory::getFilesRecursive( $dir )
      ]));

  }

}
