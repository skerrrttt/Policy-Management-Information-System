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
        Schema::create('proposal_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proposals_id');
            $table->text('file_paths'); 
            $table->string('version'); 
            $table->timestamps();
        
            $table->foreign('proposals_id')->references('id')->on('proposals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_version');
    }
};
