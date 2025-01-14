<?php

namespace App\DataTransferObjects;

class JabatanDto
{
    public $jbt_id;
    public $jbt_name;
    public $jbt_status;

    public function __construct($jbt_id, $jbt_name, $jbt_status=null)
    {
        $this->jbt_id = $jbt_id;
        $this->jbt_name = $jbt_name;
        $this->jbt_status = $jbt_status;
    }
}