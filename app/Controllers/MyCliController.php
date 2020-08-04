<?php
namespace App\Controllers;

class MyCliController extends BaseController
{
    protected $view_data = array(
        'msg' => 'The controller use at CLI only',
    );

    public function __construct()
    {
        if( ! is_cli())
        {
            echo "本控制器只可用於命令列環境";
            exit;
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

    protected function cli_output()
    {
        return view('cli/cli', $this->view_data);
    }
}
