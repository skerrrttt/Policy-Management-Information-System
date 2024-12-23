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
        Schema::create('bor_meeting_agenda', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('uni_agenda_id')->constrained('university_meeting_agenda')->cascadeOnDelete();
            $table->unsignedBigInteger('loc_agenda_id')->constrained('university_meeting_agenda')->cascadeOnDelete();
            $table->unsignedBigInteger('proposals_id')->constrained('university_meeting_agenda')->cascadeOnDelete();
            $table->unsignedBigInteger('ucm_id')->constrained('university_meeting_agenda')->cascadeOnDelete();
            $table->unsignedBigInteger('bcm_id')->constrained('bor_meeting')->cascadeOnDelete();

            $table->unsignedInteger('proposals_status_id');
            $table->unsignedInteger('requested_action_id');
            
            // Foreign key constraint with a custom, shorter name
            $table->foreign('proposals_status_id')->references('id')->on('proposals_status');
            $table->foreign('requested_action_id')->references('id')->on('requested_action');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bor_meeting_agenda');
    }
};
