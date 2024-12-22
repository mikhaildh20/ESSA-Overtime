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

    public function dpo_mskaryawan(){
        return $this->hasMany(Karyawan::class,'jbt_id','jbt_id');//kiri column asal, kanan column foreign key
    }
}
