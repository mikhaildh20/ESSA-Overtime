<?php

namespace App\DataTransferObjects;

class PengajuanDto
{
    public $pjn_id;
    public $jpj_name;
    public $pjn_status;
    public $pjn_tanggal;
    public $pjn_id_alternative;
    public $pjn_keterangan;
    public $pjn_pdf;
    public $pjn_excel;

    public function __construct($pjn_id, $jpj_name, $pjn_status, $pjn_tanggal, $pjn_id_alternative = null ,$pjn_keterangan = null, $pjn_pdf = null, $pjn_excel = null)
    {
        $this->pjn_id = $pjn_id;
        $this->jpj_name = $jpj_name;
        $this->pjn_status = $pjn_status;
        $this->pjn_tanggal = $pjn_tanggal;
        $this->pjn_id_alternative = $pjn_id_alternative;
        $this->pjn_keterangan = $pjn_keterangan;
        $this->pjn_pdf = $pjn_pdf;
        $this->pjn_excel = $pjn_excel;
    }
}