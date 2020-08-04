<?php
namespace App\Controllers;

class MyFrontController extends BaseController
{
    protected $request;
    /*傳送到view的資料陣列，為了讓MY_Controller能以權限機制操控，而宣告在這裡提供繼承*/
    private $view_data = array(
        'site_name' => NULL,
        'site_title' => NULL,
        'page_title' => NULL,
        'page_uri' => NULL,
        'query_string' => NULL,
        'return_uri' => NULL,
        'error_msg' => NULL,
        'layout_options' => 'sidebar-mini layout-fixed',
    );
    /*view的各版面區塊設定*/
    private $view_config = array(
        'base' => 'template/front/page_base',
        'metadata' => NULL,
        'common_js' => 'template/front/common_js',
        'js' => NULL,
        'common_css' => 'template/front/common_css',
        'css' => NULL,
        'header' => 'template/front/header',
        'content_header' => 'template/front/content_header',
        'content_footer' => 'template/front/content_footer',
        'left_side' => 'template/front/left_side',
        'right_side' => 'template/front/right_side',
        'error_msg' => 'template/front/error_msg',
        'content' => 'template/front/content',
        'footer' => 'template/front/footer',
        'extra' => 'template/front/extra',
        'common_nle_js' => 'template/front/common_nle_js',   //Not Loading and Execution
        'nle_js' => NULL,
        'common_nle_css' => 'template/front/common_nle_css',
        'nle_css' => NULL,
    );

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->view_data['page_uri'] = ( ! isset($_SERVER['PATH_INFO']))? '/': $_SERVER['PATH_INFO'];
        $this->view_data['query_string'] = ( ! isset($_SERVER['QUERY_STRING']))? '': $_SERVER['QUERY_STRING'];

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

    protected function set_view_config($attr, $value)
    {
        $this->view_config[$attr] = $value;
    }

    protected function get_view_config($attr = NULL)
    {
        if( ! is_null($attr) && isset($this->view_config[$attr]))
        {
            return $this->view_config[$attr];
        }
        else
        {
            return $this->view_config;
        }
    }

    protected function render($content_page)
    {
        $config = config('Config\\Site');

        $this->view_data['site_name'] = $config->siteName;
        if( ! isset($this->view_data['page_title']) OR is_null($this->view_data['page_title']))
        {
            $this->view_data['site_title'] = $config->siteName;
        }
        else
        {
            $this->view_data['site_title'] = $config->siteName.'-'.$this->view_data['page_title'];
        }

        $this->view_config['content'] = $content_page;
        $this->view_data['___VIEW_CONFIG___'] = $this->view_config;
        return view($this->view_config['base'], $this->view_data);
    }

}
