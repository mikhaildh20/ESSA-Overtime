<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'dpo_msnotifikasi';
    protected $primaryKey = 'ntf_id';
    public $incrementing = true;
    protected $fillable = [
        'ntf_message',
        'ntf_status',
        'ntf_created_by',
        'ntf_modified_by',
        'ntf_pjn_id'
    ];

    /**
     * Relasi ke model pengajuan
     */
    public function dpo_trpengajuanovertime()
    {
        return $this->belongsTo(Pengajuan::class, 'ntf_pjn_id', 'pjn_id');
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
            'ntf_id',     
            'ntf_message',
            'ntf_status', 
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'ntf_message'; // Default ke ntf_status jika tidak valid
    }
}
