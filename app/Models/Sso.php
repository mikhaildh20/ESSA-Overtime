<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sso extends Model
{
    use HasFactory;

    protected $table = 'dpo_sso';
    protected $primaryKey = 'sso_id';
    public $incrementing = true;
    protected $fillable = ['kry_id','sso_level','sso_created_by','sso_modified_by'];
    protected $dates = ['deleted_at'];

    /**
     * Relasi ke model Karyawan
     */
    public function dpo_mskaryawan()
    {
        return $this->belongsTo(Karyawan::class, 'kry_id', 'kry_id');
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
            'sso_id',       // ID SSO
            'kry_id',       // ID Karyawan
            'sso_level',    // Level SSO
            'sso_created_by', // Pembuat SSO
            'sso_modified_by', // Pengubah SSO
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'sso_id';
    }
}
