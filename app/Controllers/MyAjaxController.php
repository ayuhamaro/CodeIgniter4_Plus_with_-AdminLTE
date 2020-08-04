<?php
namespace App\Controllers;

class MyAjaxController extends BaseController
{
    protected $request;

    private $view_data = array(
        'success' => FALSE,
        'statusCode' => '1000',
        'errorMsg' => 'Not logged in',
    );

    public function __construct()
    {
        $this->request = \Config\Services::request();

        if( ! session_id())
        {
            session_start();
        }
    }

    protected function set_view_data($attr, $value)
    {
        $this->view_data[$attr] = $value;
    }

    protected function get_view_data($attr = NULL)
    {
        if( ! is_null($attr) && isset($this->view_data[$attr]))
        {
            return $this->view_data[$attr];
        }
        else
        {
            return $this->view_data;
        }
    }

    protected function ajax_output()
    {
        return view('api/ajax', array('json' => json_encode($this->view_data, JSON_UNESCAPED_UNICODE)));
    }
}
