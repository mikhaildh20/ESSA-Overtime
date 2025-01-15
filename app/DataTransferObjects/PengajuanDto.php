<?php

namespace App\DataTransferObjects;

class PengajuanDto
{
    public $pjn_id;
    public $jpj_name;
    public $pjn_status;
    public $pjn_tanggal;

    public function __construct($pjn_id, $jpj_name, $pjn_status, $pjn_tanggal)
    {
        $this->pjn_id = $pjn_id;
        $this->jpj_name = $jpj_name;
        $this->pjn_status = $pjn_status;
        $this->pjn_tanggal = $pjn_tanggal;
    }
}