<?php

namespace App\DataTransferObjects;

class PengajuanDto
{
    public $pjn_id_alternative;
    public $kry_name;
    public $jpj_name;
    public $pjn_status;

    public function __construct($pjn_id_alternative, $kry_name, $jpj_name, $pjn_status)
    {
        $this->pjn_id_alternative = $pjn_id_alternative;
        $this->kry_name = $kry_name;
        $this->jpj_name = $jpj_name;
        $this->pjn_status = $pjn_status;
    }
}