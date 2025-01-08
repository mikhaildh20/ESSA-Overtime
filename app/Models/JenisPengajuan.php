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

}
