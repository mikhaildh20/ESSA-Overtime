<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pengajuan;

class JenisPengajuan extends Model
{
    use HasFactory;

    protected $table = 'dpo_msjenispengajuan';
    protected $primaryKey = 'jen_id';
    public $incrementing = true;

    protected $fillable = [
        'jen_id_alternative',
        'jen_nama',
        'jen_deskripsi',
        'jen_status',
        'jen_created_date',
        'jen_modified_date'
    ];

    const CREATED_AT = 'jen_created_date';
    const UPDATED_AT = 'jen_modified_date';

    /**
     * Relasi ke model Pengajuan
     */
    public function dpo_trpengajuanovertime()
    {
        return $this->hasMany(Pengajuan::class, 'jen_id', 'jen_id'); // Relasi dengan model Karyawan
    }

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
            'jen_id_alternative',
            'jen_nama',
            'jen_deskripsi',
            'jen_status',
            'jen_created_date',
            'jen_modified_date'
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'jen_name'; // Default ke jbt_name jika tidak valid
    }
}
