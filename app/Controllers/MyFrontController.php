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
    protected $view_base = 'template/front/page_base';
    protected $view_metadata = NULL;
    protected $view_common_js = 'template/front/common_js';
    protected $view_js = NULL;
    protected $view_common_css = 'template/front/common_css';
    protected $view_css = NULL;
    protected $view_header = 'template/front/header';
    protected $view_content_header = 'template/front/content_header';
    protected $view_content_footer = 'template/front/content_footer';
    protected $view_left_side = 'template/front/left_side';
    protected $view_right_side = 'template/front/right_side';
    protected $view_error_msg = 'template/front/error_msg';
    protected $view_content = 'template/front/content';
    protected $view_footer = 'template/front/footer';
    protected $view_extra = 'template/front/extra';
    protected $view_common_nle_js = 'template/front/common_nle_js';
    protected $view_nle_js = NULL;
    protected $view_common_nle_css = 'template/front/common_nle_css';
    protected $view_nle_css = NULL;

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

    public function __destruct()
    {
        echo $this->render();
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

    protected function render()
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

        $view_config = array(
            'common_js' => $this->view_common_js,
            'js' => NULL,
            'common_css' => $this->view_common_css,
            'css' => NULL,
            'header' => $this->view_header,
            'content_header' => $this->view_content_header,
            'content_footer' => $this->view_content_footer,
            'left_side' => $this->view_left_side,
            'right_side' => $this->view_right_side,
            'error_msg' => $this->view_error_msg,
            'content' => $this->view_content,
            'footer' => $this->view_footer,
            'extra' => $this->view_extra,
            'common_nle_js' => $this->view_common_nle_js,
            'nle_js' => NULL,
            'common_nle_css' => $this->view_common_nle_css,
            'nle_css' => NULL,
        );

        $this->view_data['___VIEW_CONFIG___'] = $view_config;
        return view($this->view_base, $this->view_data);
    }

}
