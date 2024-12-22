<?php

namespace App\DataTransferObjects;

class KaryawanDto
{
    public $kry_id_alternative;
    public $kry_name;
    public $jbt_name;
    public $kry_username;
    public $kry_email;
    public $kry_status;

    public function __construct($kry_id_alternative, $kry_name, $jbt_name, $kry_username, $kry_email, $kry_status)
    {
        $this->kry_id_alternative = $kry_id_alternative;
        $this->kry_name = $kry_name;
        $this->jbt_name = $jbt_name;
        $this->kry_username = $kry_username;
        $this->kry_email = $kry_email;
        $this->kry_status = $kry_status;
    }
}