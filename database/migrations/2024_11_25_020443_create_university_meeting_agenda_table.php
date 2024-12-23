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
        Schema::create('university_meeting_agenda', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('lma_id')->constrained('local_meeting_agenda')->cascadeOnDelete(); 
            $table->unsignedBigInteger('lmap_id')->constrained('local_meeting_agenda')->cascadeOnDelete(); 
            $table->unsignedBigInteger('ucm_id')->constrained('university_council_meeting')->cascadeOnDelete(); 
            $table->unsignedInteger('proposals_id');
            $table->unsignedInteger('requested_action_id');
            $table->unsignedInteger('proposals_status_id');


            $table->foreign('proposals_status_id')->references('id')->on('proposals_status');
            $table->foreign('requested_action_id')->references('id')->on('requested_action');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_meeting_agenda');
    }
};
