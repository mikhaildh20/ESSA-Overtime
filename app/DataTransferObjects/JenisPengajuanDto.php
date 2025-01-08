<?php

namespace App\DataTransferObjects;

class JenisPengajuanDto
{
    public $jpj_id;
    public $jpj_name;
    public $jpj_status;

    public function __construct($jpj_id, $jpj_name, $jpj_status=null)
    {
        $this->jpj_id = $jpj_id;
        $this->jpj_name = $jpj_name;
        $this->jpj_status = $jpj_status;
    }
}