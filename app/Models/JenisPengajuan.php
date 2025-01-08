<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPengajuan extends Model
{
    use HasFactory;

    protected $table = 'dpo_msjenispengajuan';
    protected $primaryKey = 'jpj_id';
    public $incrementing = true;

    protected $fillable = [
        'jpj_name',
        'jpj_status'
    ];


    /**
     * Sanitasi nama kolom untuk query 'order by'
     * Menjamin hanya kolom yang aman yang digunakan untuk pengurutan.
     *
     * @param string $column
     * @return string
     */
    public static function sanitizeColumn(string $column): string
    {
        // Daftar kolom yang aman untuk diurutkan
        $allowedColumns = [
            'jpj_id',      // ID Jenis Pengajuan
            'jpj_name',    // Nama Jenis Pengajuan
            'jpj_status',  // Status Jenis Pengajuan
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'jpj_name'; // Default ke jbt_name jika tidak valid
    }
}
