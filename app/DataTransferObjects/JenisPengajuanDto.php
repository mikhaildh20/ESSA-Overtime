<?php
namespace App\DataTransferObjects;

class JenisPengajuanDto
{
    public $jen_id;
    public $jen_id_alternative;
    public $jen_nama;
    public $jen_deskripsi;
    public $jen_status;

    public function __construct($jen_id, $jen_id_alternative, $jen_nama, $jen_deskripsi, $jen_status)
    {
        $this->jen_id = $jen_id;
        $this->jen_id_alternative = $jen_id_alternative;
        $this->jen_nama = $jen_nama;
        $this->jen_deskripsi = $jen_deskripsi;
        $this->jen_status = $jen_status;
    }
}
