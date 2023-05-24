<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelCrud extends Model
{
    protected $table = "inventaris";
    protected $primaryKey = "id";
    protected $allowedFields = ['kode', 'nama', 'jenis', 'jumlah'];

    function cari($katakunci)
    {
        
        $builder = $this->table("inventaris");
        $arr_katakunci = explode(" ", $katakunci);
        for ($x = 0; $x < count($arr_katakunci); $x++) {
            $builder->orLike('kode', $arr_katakunci[$x]);
            $builder->orLike('nama', $arr_katakunci[$x]);
            $builder->orLike('jumlah', $arr_katakunci[$x]);
        }
        return $builder;
    }
}
