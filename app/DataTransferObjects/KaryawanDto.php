<?php

namespace App\DataTransferObjects;

class KaryawanDto
{
    public $kry_id_alternative;
    public $kry_name;
    public $kry_username;
    public $kry_email;
    public $kry_status;

    public function __construct($kry_id_alternative, $kry_name, $kry_username, $kry_email, $kry_status)
    {
        $this->kry_id_alternative;
        $this->kry_name;
        $this->kry_username;
        $this->kry_email;
        $this->kry_status;
    }
}