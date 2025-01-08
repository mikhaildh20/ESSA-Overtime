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
            $table->id('sso_id');  // Auto-incrementing primary key
            $table->unsignedBigInteger('kry_id');
            $table->integer('sso_level');
            $table->integer('sso_status');
            $table->string('sso_created_by');
            $table->string('sso_modified_by');
            $table->timestamps();
            
            // Add a unique constraint on the composite key
            $table->unique(['kry_id', 'sso_level']);
            
            // Foreign key constraint
            $table->foreign('kry_id')
                  ->references('kry_id')
                  ->on('dpo_mskaryawan')
                  ->onDelete('restrict');
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
