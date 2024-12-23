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
        Schema::create('local_meeting_agenda', function (Blueprint $table) {
            $table->increments('id'); // Primary key automatically set on `id`
            $table->unsignedBigInteger('proposals_id')->constrained('proposals')->cascadeOnDelete();
            $table->unsignedInteger('local_council_meeting_id');
            $table->unsignedInteger('proposals_status_id');
            $table->unsignedInteger('requested_action_id');
            

            // Foreign keys
            $table->foreign('local_council_meeting_id')->references('id')->on('local_council_meeting')->onDelete('set null'); // Set the foreign key to null when the parent is deleted            $table->unsignedInteger('proposals_status_id');

            $table->foreign('proposals_status_id')->references('id')->on('proposals_status');
            $table->foreign('requested_action_id')->references('id')->on('requested_action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_meeting_agenda');
    }
};
