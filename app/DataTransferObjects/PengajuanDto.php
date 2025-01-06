<?php

namespace App\DataTransferObjects;

class PengajuanDto
{
    public $id;
    public $nidn;
    public $nama;
    public $jenis_pengajuan;
    public $tanggal_pengajuan;
    public $status;

    public function __construct($id, $nidn, $nama, $jenis_pengajuan, $tanggal_pengajuan, $status)
    {
        $this->id = $id;
        $this->nidn = $nidn;
        $this->nama = $nama;
        $this->jenis_pengajuan = $jenis_pengajuan;
        $this->tanggal_pengajuan = $tanggal_pengajuan;
        $this->status = $status;
    }
}
