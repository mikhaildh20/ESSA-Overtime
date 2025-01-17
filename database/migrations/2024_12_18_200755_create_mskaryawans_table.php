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
        Schema::create('dpo_mskaryawan', function (Blueprint $table) {
            $table->id('kry_id');
            $table->string('kry_id_alternative');
            $table->string('kry_name');
            $table->unsignedBigInteger('jbt_id');
            $table->string('kry_username');
            $table->string('kry_password');
            $table->string('kry_email');
            $table->string('kry_status');
            $table->string('kry_created_by');
            $table->string('kry_modified_by')->nullable();
            $table->timestamps();

            // Add a unique constraint on the composite key
            $table->unique('kry_id_alternative');

            $table->foreign('jbt_id')->references('jbt_id')->on('dpo_msjabatan')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mskaryawans');
    }
};
