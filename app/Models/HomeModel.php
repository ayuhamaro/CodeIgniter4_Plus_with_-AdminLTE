<?php namespace App\Models;

use App\Models\MyModel;

class HomeModel extends MyModel
{
    public function show_databases()
    {
        $query = $this->db->query('SHOW DATABASES;');
        $results = $query->getResultArray();

        return $results;
    }

}
