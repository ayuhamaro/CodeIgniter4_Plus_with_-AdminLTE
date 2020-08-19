<?php namespace App\Controllers;

class Home extends MyFrontController
{
	public function index()
	{
        //$homeModel = new \App\Models\HomeModel();
        $config = config('Config\\Site');
        $paginationLib = new \App\Libraries\MyPaginationLib();

        $this->set_view_data('page_title', 'Hi! I\'m '.$config->siteName);
        $this->set_view_data('msg', '簡單輕鬆套上全功能版型 & 自動渲染視圖！');


        $page_num = (is_null($this->request->getGet('page_num')))?'1': $this->request->getGet('page_num');


        $paginationLib->setting($page_num, 10, 130);
        $pagination_link = $paginationLib->pagination_link();
        $this->set_view_data('pagination_link', $pagination_link);
        $pagination_select = $paginationLib->pagination_select();
        $this->set_view_data('pagination_select', $pagination_select);


        $this->view_content = 'front/home/home__index';
	}



}
