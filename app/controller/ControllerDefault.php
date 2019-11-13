<?php

use Swiftly\Base\Controller;

Class ControllerDefault extends Controller
{
    public function index()
    {

        $this->setOutput($this->render('test', []));

    }
}