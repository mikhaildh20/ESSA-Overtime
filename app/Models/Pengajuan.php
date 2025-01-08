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

    
}
