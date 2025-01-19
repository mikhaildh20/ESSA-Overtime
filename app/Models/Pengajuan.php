<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengajuan extends Model
{
    use HasFactory;
    protected $table = 'dpo_trpengajuanovertime';
    protected $primaryKey = 'pjn_id';
    public $incrementing = true;
    protected $fillable = [
        'pjn_id_alternative',
        'pjn_type',
        'pjn_description',
        'pjn_excel_proof',
        'pjn_pdf_proof',
        'pjn_review_notes',
        'pjn_status',
        'pjn_created_by',
        'pjn_modified_by',
        'pjn_kry_id'
    ];

    /**
     * Relasi ke model Jenis Pengajuan
     */
    public function dpo_msjenispengajuan()
    {
        return $this->belongsTo(JenisPengajuan::class, 'pjn_type', 'jpj_id');
    }

    /**
     * Relasi ke model Karyawan
     */
    public function dpo_mskaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'pjn_kry_id', 'kry_id');
    }

    /**
     * Relasi ke model notifikasi
     */
    public function dpo_msnotifikasi()
    {
        return $this->hasOne(Notifikasi::class, 'pjn_id', 'ntf_pjn_id');
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
            'pjn_type',
            'pjn_status',
            'pjn_kry_id',
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'pjn_status'; // Default ke pjn_type jika tidak valid
    }
}
