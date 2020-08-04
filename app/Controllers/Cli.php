<?php namespace App\Controllers;

class Cli extends MyCliController
{
    public function index()
    {
        return $this->cli_output();
    }
}
