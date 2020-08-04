<?php namespace App\Libraries;

class MyPaginationLib
{
    private $page_num;
    private $per_page;
    private $total_rows;
    private $page_count;
    private $path_info = '/';
    private $query_string = '';
    private $uri_pattern = '';

    public function __construct($page_num = 1, $per_page = 10, $total_rows = 1, $query_string_segment = 'page_num')
    {
        $this->setting($page_num, $per_page, $total_rows, $query_string_segment);
    }

    public function setting($page_num = 1, $per_page = 10, $total_rows = 1, $query_string_segment = 'page_num')
    {
        $this->page_num = (int)$page_num;
        $this->per_page = (int)$per_page;
        $this->total_rows = (int)$total_rows;
        $this->page_count = (int)ceil($this->total_rows / $this->per_page);

        $this->path_info = ( ! isset($_SERVER['PATH_INFO']))? '/': $_SERVER['PATH_INFO'];
        $this->query_string = ( ! isset($_SERVER['QUERY_STRING']))? '': $_SERVER['QUERY_STRING'];
        $output = array();
        if($this->query_string !== '')
        {
            parse_str($this->query_string, $output);
            if(isset($output[$query_string_segment]))
            {
                unset($output[$query_string_segment]);
            }
        }
        if(count($output) == 0)
        {
            $this->uri_pattern = $this->path_info.'?'.$query_string_segment.'=%s';
        }
        else
        {
            $this->uri_pattern = $this->path_info.'?'.$query_string_segment.'=%s&'.http_build_query($output);
        }
    }

    public function pagination_link($show_page_count = 7)
    {
        $pagination_array = array();

        if($this->page_count == 1)
        {
            //$pagination_array[] = array(
            //    'href' => NULL,
            //    'class' => 'page-link page-link-disabled',
            //    'text' => '1',
            //);
        }
        elseif($this->page_count <= $show_page_count)
        {
            if($this->page_num !== 1)
            {
                $pagination_array[] = array(
                    'href' => (string)1,
                    'class' => 'page-link',
                    'text' => '第一頁',
                );
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, ($this->page_num - 1)),
                    'class' => 'page-link',
                    'text' => '上一頁',
                );
            }

            for($i = 1; $i <= $this->page_count; $i++)
            {
                if($i == $this->page_num)
                {
                    $pagination_array[] = array(
                        'href' => NULL,
                        'class' => 'page-link page-link-disabled',
                        'text' => (string)$i,
                    );
                }
                else
                {
                    $pagination_array[] = array(
                        'href' => sprintf($this->uri_pattern, $i),
                        'class' => 'page-link',
                        'text' => (string)$i,
                    );
                }
            }

            if($this->page_num !== $this->page_count)
            {
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, $i),
                    'class' => 'page-link',
                    'text' => '下一頁',
                );
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, $this->page_count),
                    'class' => 'page-link',
                    'text' => '最末頁',
                );
            }
        }
        else
        {
            if($this->page_num !== 1)
            {
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, 1),
                    'class' => 'page-link',
                    'text' => '第一頁',
                );
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, $this->page_num - 1),
                    'class' => 'page-link',
                    'text' => '上一頁',
                );
            }

            if($this->page_num <= ceil($show_page_count / 2))
            {
                // 頭
                for($i = 1; $i <= $show_page_count; $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'href' => NULL,
                            'class' => 'page-link page-link-disabled',
                            'text' => (string)$i,
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'href' => sprintf($this->uri_pattern, $i),
                            'class' => 'page-link',
                            'text' => (string)$i,
                        );
                    }
                }
            }
            elseif($this->page_num >= $this->page_count - floor($show_page_count / 2))
            {
                // 尾
                for($i = ($this->page_count - $show_page_count) + 1; $i <= $this->page_count; $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'href' => NULL,
                            'class' => 'page-link page-link-disabled',
                            'text' => (string)$i,
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'href' => sprintf($this->uri_pattern, $i),
                            'class' => 'page-link',
                            'text' => (string)$i,
                        );
                    }
                }
            }
            else
            {
                // 中
                $begin = $this->page_num - floor($show_page_count / 2);
                for($i = $begin; $i <= $this->page_num + floor($show_page_count / 2); $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'href' => NULL,
                            'class' => 'page-link page-link-disabled',
                            'text' => (string)$i,
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'href' => sprintf($this->uri_pattern, $i),
                            'class' => 'page-link',
                            'text' => (string)$i,
                        );
                    }
                }
            }

            if($this->page_num !== $this->page_count)
            {
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, $this->page_num + 1),
                    'class' => 'page-link',
                    'text' => '下一頁',
                );
                $pagination_array[] = array(
                    'href' => sprintf($this->uri_pattern, $this->page_count),
                    'class' => 'page-link',
                    'text' => '最末頁',
                );
            }
        }
        $pagination_array[] = array(
            'href' => NULL,
            'class' => 'page-link page-link-disabled',
            'text' => sprintf('共有&nbsp;%s&nbsp;筆，&nbsp;%s&nbsp;頁', $this->total_rows, $this->page_count),
        );

        return view('template/pagination/link', array('pagination' => $pagination_array));
    }

    public function pagination_select($show_page_count = 7)
    {
        $pagination_array = array();

        if($this->page_count == 1)
        {
            $pagination_array[] = array(
                'disabled' => FALSE,
                'selected' => TRUE,
                'value' => sprintf($this->uri_pattern, 1),
                'text' => '第 1 頁',
            );
        }
        elseif($this->page_count <= $show_page_count)
        {
            for($i = 1; $i <= $this->page_count; $i++)
            {
                if($i == $this->page_num)
                {
                    $pagination_array[] = array(
                        'disabled' => TRUE,
                        'selected' => TRUE,
                        'value' => sprintf($this->uri_pattern, $i),
                        'text' => sprintf('第 %s 頁', $i),
                    );
                }
                else
                {
                    $pagination_array[] = array(
                        'disabled' => FALSE,
                        'selected' => FALSE,
                        'value' => sprintf($this->uri_pattern, $i),
                        'text' => sprintf('第 %s 頁', $i),
                    );
                }
            }
        }
        else
        {
            if($this->page_num > ceil($show_page_count / 2) + 1)
            {
                $pagination_array[] = array(
                    'disabled' => FALSE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, 1),
                    'text' => '第 1 頁',
                );
                $pagination_array[] = array(
                    'disabled' => TRUE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, 1),
                    'text' => '...',
                );
            }

            if($this->page_num == ceil($show_page_count / 2) + 1)
            {
                $pagination_array[] = array(
                    'disabled' => FALSE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, 1),
                    'text' => '第 1 頁',
                );
            }

            if($this->page_num <= ceil($show_page_count / 2))
            {
                // 頭
                for($i = 1; $i <= $show_page_count; $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => TRUE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => FALSE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                }
            }
            elseif($this->page_num >= $this->page_count - floor($show_page_count / 2))
            {
                // 尾
                for($i = ($this->page_count - $show_page_count) + 1; $i <= $this->page_count; $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => TRUE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => FALSE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                }
            }
            else
            {
                // 中
                $begin = $this->page_num - floor($show_page_count / 2);
                for($i = $begin; $i <= $this->page_num + floor($show_page_count / 2); $i++)
                {
                    if($i == $this->page_num)
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => TRUE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                    else
                    {
                        $pagination_array[] = array(
                            'disabled' => FALSE,
                            'selected' => FALSE,
                            'value' => sprintf($this->uri_pattern, $i),
                            'text' => sprintf('第 %s 頁', $i),
                        );
                    }
                }
            }

            if($this->page_num == $this->page_count - ceil($show_page_count / 2))
            {
                $pagination_array[] = array(
                    'disabled' => FALSE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, $this->page_count),
                    'text' => sprintf('第 %s 頁', $this->page_count),
                );
            }

            if($this->page_num < $this->page_count - ceil($show_page_count / 2))
            {
                $pagination_array[] = array(
                    'disabled' => TRUE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, $this->page_count),
                    'text' => '...',
                );
                $pagination_array[] = array(
                    'disabled' => FALSE,
                    'selected' => FALSE,
                    'value' => sprintf($this->uri_pattern, $this->page_count),
                    'text' => sprintf('第 %s 頁', $this->page_count),
                );
            }
        }

        return view('template/pagination/select', array('pagination' => $pagination_array));
    }

}
