<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Model
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

    public function dpo_msjabatan()
    {
        return $this->belongsTo(Jabatan::class,'jbt_id','jbt_id');//kiri column sumber, kanan column utama 
    }
}
