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
        Schema::create('dpo_trpengajuanovertime', function (Blueprint $table) {
            $table->id('pjn_id');
            $table->string('pjn_id_alternative');
            $table->unsignedBigInteger('jen_id');
            $table->string('pjn_deskripsi');
            $table->string('pjn_excel_proof');
            $table->string('pjn_pdf_proof');
            $table->string('pjn_catatan');
            $table->string('pjn_status');
            $table->unsignedBigInteger('kry_id');
            $table->timestamp('pjn_created_date')->useCurrent();
            $table->timestamp('pjn_modified_date')->useCurrent();

            // Add a unique constraint on the composite key
            $table->unique('pjn_id_alternative');

            $table->foreign('kry_id')->references('kry_id')->on('dpo_mskaryawan')->onDelete('restrict');
            $table->foreign('jen_id')->references('jen_id')->on('dpo_msjenispengajuan')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dpo_trpengajuanovertime');
    }
};
