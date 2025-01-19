<?php

namespace App\DataTransferObjects;

class NotifikasiDto{
    public $ntf_id;
    public $ntf_message;
    public $pjn_id_alternative;
    public $pjn_status;
    public $ntf_status;
    public $ntf_from;
    public $ntf_tanggal;

    public function __construct($ntf_id, $ntf_message, $pjn_id_alternative,$pjn_status,$ntf_status, $ntf_from, $ntf_tanggal){
        $this->ntf_id = $ntf_id;
        $this->ntf_message = $ntf_message;
        $this->pjn_id_alternative = $pjn_id_alternative;
        $this->pjn_status = $pjn_status;
        $this->ntf_status = $ntf_status;
        $this->ntf_from = $ntf_from;
        $this->ntf_tanggal = $ntf_tanggal;
    }
}