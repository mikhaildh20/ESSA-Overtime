<?php

namespace App\DataTransferObjects;

class SsoDto
{
    public $sso_id;
    public $kry_name;
    public $jbt_name;
    public $sso_level;

    public function __construct($sso_id, $kry_name, $jbt_name, $sso_level)
    {
        $this->sso_id = $sso_id;
        $this->kry_name = $kry_name;
        $this->jbt_name = $jbt_name;
        $this->sso_level = $sso_level;
    }
}