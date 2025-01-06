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
        Schema::create('dpo_msjenispengajuan', function (Blueprint $table) {
            $table->id('jen_id');
            $table->string('jen_id_alternative');
            $table->string('jen_nama');
            $table->string('jen_deskripsi');
            $table->string('jen_status');
            $table->timestamp('jen_created_date')->useCurrent();
            $table->timestamp('jen_modified_date')->useCurrent();
        
            // Add a unique constraint on the composite key
            $table->unique('jen_id_alternative');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msjenispengajuan');
    }
};
