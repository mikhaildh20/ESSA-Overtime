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
            $table->unsignedBigInteger('jbt_id');
            $table->primary(['kry_id', 'jbt_id']);
            $table->timestamps();
        
            $table->foreign('kry_id')->references('kry_id')->on('dpo_mskaryawan')->onDelete('restrict');
            $table->foreign('jbt_id')->references('jbt_id')->on('dpo_msjabatan')->onDelete('restrict');
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
