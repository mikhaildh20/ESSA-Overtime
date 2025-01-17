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
        Schema::create('dpo_msnotifikasi', function (Blueprint $table) {
            $table->id('ntf_id');
            $table->string('ntf_message');
            $table->string('ntf_status');
            $table->string('ntf_created_by');
            $table->string('ntf_modified_by')->nullable();
            $table->unsignedBigInteger('ntf_pjn_id');
            $table->timestamps();

            $table->foreign('ntf_pjn_id')
                ->references('pjn_id')
                ->on('dpo_trpengajuanovertime')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msnotifikasi');
    }
};
