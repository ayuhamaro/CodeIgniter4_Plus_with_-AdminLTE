<?php namespace App\Models;

use CodeIgniter\Model;

class MyModel extends Model
{
    protected $main_db = '';
    protected $db;

    public function __construct()
    {
        $config = config('Config\\Database');
        $this->main_db = $config->default['database'];
        $this->db = \Config\Database::connect();
    }

    protected function _sp_free_result($query)
    {
        // http://php.net/manual/en/mysqli.quickstart.stored-procedures.php
        do {
            if ($res = $query->conn_id->store_result()) {
                $res->free();
            } else {
                if ($query->conn_id->errno) {
                    echo "Store failed: (" . $query->conn_id->errno . ") " . $query->conn_id->error;
                }
            }
        } while ($query->conn_id->more_results() && $query->conn_id->next_result());
    }

    protected function _filter2rule($base_rule = NULL, $filter = array(), $date_field = NULL, $begin_date = NULL, $end_date = NULL)
    {
        $rule_field = array();
        $rule_value = array();
        //載入基本條件
        if( ! is_null($base_rule) AND is_string($base_rule))
        {
            $rule_field[] = $base_rule;
        }
        //建立非日期的組合條件
        if(is_array($filter) AND count($filter) >= 1)
        {
            foreach($filter as $key => $value)
            {
                $rule_field[] = "$key = ?";
                $rule_value[] = $value;
            }
        }
        // Date => DateTime
        if( ! is_null($begin_date) AND strlen($begin_date) == 10)
        {
            $begin_date = date_format(date_create($begin_date), "Y-m-d 00:00:00");
        }
        if( ! is_null($end_date) AND strlen($end_date) == 10)
        {
            $end_date = date_format(date_create($end_date), "Y-m-d 23:59:59");
        }
        //建立日期的組合條件
        if( ! is_null($begin_date) AND ! is_null($end_date))
        {
            $rule_field[] = "$date_field BETWEEN ? AND ?";
            $rule_value[] = $begin_date;
            $rule_value[] = $end_date;
        }
        elseif( ! is_null($begin_date))
        {
            $rule_field[] = "$date_field >= ?";
            $rule_value[] = $begin_date;
        }
        elseif( ! is_null($end_date))
        {
            $rule_field[] = "$date_field <= ?";
            $rule_value[] = $end_date;
        }
        $rule_field_str = join(' AND ', $rule_field);
        //回傳
        return array('rule_field_str' => $rule_field_str,
            'rule_value_array' => $rule_value);
    }

    protected function _row_num_offset($page = 1, $row_num = 20)
    {
        return ($page > 1)? $row_num * ($page - 1): 0;
    }

    protected function _datetime()
    {
        return date('Y-m-d H:i:s');
    }

}
