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
        Schema::create('university_council_meeting', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_description')->nullable();
            $table->date('meeting_date')->nullable();
            $table->integer('quarter')->nullable();
            $table->unsignedInteger('council_type_id');
            $table->unsignedInteger('meeting_modality_id');
            $table->unsignedInteger('meeting_venue_id');


            $table->foreign('council_type_id')->references('id')->on('council_type');
            $table->foreign('meeting_modality_id')->references('id')->on('meeting_modality');
            $table->foreign('meeting_venue_id')->references('id')->on('meeting_venue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_council_meeting');
    }
};
