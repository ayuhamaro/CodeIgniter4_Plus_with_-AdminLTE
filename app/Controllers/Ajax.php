<?php namespace App\Controllers;

class Ajax extends MyAjaxController
{
    public function index()
    {
        return $this->ajax_output();
    }
}
