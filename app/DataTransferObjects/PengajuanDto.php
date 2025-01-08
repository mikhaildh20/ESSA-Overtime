<?php

namespace App\DataTransferObjects;

class PengajuanDto
{
    public $pjn_id;
    public $pjn_id_alternative;
    public $kry_name;
    public $jpj_name;
    public $pjn_description;
    public $pjn_excel_proof;
    public $pjn_review_notes;
    public $pjn_status;

    public function __construct($pjn_id, $pjn_id_alternative, $kry_name, $jpj_name, $pjn_description, $pjn_excel_proof, $pjn_review_notes, $pjn_status)
    {
        $this->pjn_id = $pjn_id;
        $this->pjn_id_alternative = $pjn_id_alternative;
        $this->kry_name = $kry_name;
        $this->jpj_name = $jpj_name;
        $this->pjn_description = $pjn_description;
        $this->pjn_excel_proof = $pjn_excel_proof;
        $this->pjn_review_notes = $pjn_review_notes;
        $this->pjn_status = $pjn_status;
    }
}