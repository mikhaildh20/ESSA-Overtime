<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dpo_sso', function (Blueprint $table) {
            $table->unsignedBigInteger('kry_id');
            $table->Integer('level');
            $table->primary(['kry_id', 'level']);
            $table->timestamps();
        
            $table->foreign('kry_id')->references('kry_id')->on('dpo_mskaryawan')->onDelete('restrict');
        });    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sso');
    }
};
