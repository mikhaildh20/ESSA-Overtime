<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Karyawan extends Authenticatable
{
    use HasFactory;

    protected $table = 'dpo_mskaryawan';
    protected $primaryKey = 'kry_id';
    public $incrementing = true;
    
    protected $fillable = [
        'kry_id_alternative',
        'kry_jabatan',
        'kry_name',
        'kry_username',
        'kry_password',
        'kry_email',
        'kry_status',
        'kry_created_by',
        'kry_modified_by'
    ];

    /**
     * Relasi ke model Jabatan
     */
    public function dpo_msjabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jbt_id', 'jbt_id'); // Relasi dengan model Jabatan
    }

    /**
     * Relasi ke model Sso
     */
    public function dpo_sso()
    {
        return $this->hasMany(Sso::class, 'kry_id', 'kry_id'); // Relasi dengan model Sso
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
            'kry_id',        // ID Karyawan
            'kry_id_alternative', // ID Alternatif Karyawan
            'kry_name',      // Nama Karyawan
            'kry_username',  // Username Karyawan
            'kry_email',     // Email Karyawan
            'kry_status',    // Status Karyawan
            'kry_created_by', // Pembuat Karyawan
            'kry_modified_by', // Pengubah Karyawan
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'kry_name'; // Default ke kry_name jika tidak valid
    }
}
