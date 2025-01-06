<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\JenisPengajuan;
use App\Models\Karyawan;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'dpo_trpengajuanovertime';
    protected $primaryKey = 'pjn_id';
    public $incrementing = true;
    
    protected $fillable = [
        'pjn_id_alternative',
        'jen_id',
        'pjn_deskripsi',
        'pjn_excel_proof',
        'pjn_pdf_proof',
        'pjn_catatan',
        'pjn_status',
        'pjn_created_date',
        'pjn_modified_date',
        'kry_id'
    ];

    const CREATED_AT = 'pjn_created_date';
    const UPDATED_AT = 'pjn_modified_date';

    public function dpo_mskaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'kry_id', 'kry_id'); // Relasi dengan model Karyawan
    }

    public function dpo_msjenispengajuan()
    {
        return $this->belongsTo(JenisPengajuan::class, 'jen_id', 'jen_id'); // Relasi dengan model Karyawan
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
            'pjn_id_alternative',
            'jen_id',
            'pjn_deskripsi',
            'pjn_excel_proof',
            'pjn_pdf_proof',
            'pjn_catatan',
            'pjn_status',
            'pjn_created_date',
            'pjn_modified_date',
            'kry_id'
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'pjn_id_alternative'; 
    }
}
