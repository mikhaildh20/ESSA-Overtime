<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'dpo_msjabatan';
    protected $primaryKey = 'jbt_id';
    public $incrementing = true;

    protected $fillable = [
        'jbt_name',
        'jbt_status'
    ];

    /**
     * Relasi ke model Karyawan
     */
    public function dpo_mskaryawan()
    {
        return $this->hasMany(Karyawan::class, 'jbt_id', 'jbt_id'); // Relasi dengan model Karyawan
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
            'jbt_id',      // ID Jabatan
            'jbt_name',    // Nama Jabatan
            'jbt_status',  // Status Jabatan
        ];

        // Mengembalikan nama kolom yang valid, jika tidak valid, gunakan default
        return in_array($column, $allowedColumns) ? $column : 'jbt_name'; // Default ke jbt_name jika tidak valid
    }
}
