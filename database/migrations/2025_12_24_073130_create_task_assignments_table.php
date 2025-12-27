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
    Schema::create('task_assignments', function (Blueprint $table) {
        $table->id();

        // Columns first
        $table->unsignedBigInteger('task_id');
        $table->unsignedBigInteger('assigned_to');
        $table->unsignedBigInteger('assigned_by');

        // Indexes (optional but recommended)
        $table->index('task_id');
        $table->index('assigned_to');
        $table->index('assigned_by');

        // Foreign Keys (explicit names)
        $table->foreign('task_id', 'fk_task_assignments_task_id')
              ->references('id')
              ->on('tasks')
              ->onDelete('cascade');

        $table->foreign('assigned_to', 'fk_task_assignments_assigned_to')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');

        $table->foreign('assigned_by', 'fk_task_assignments_assigned_by')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
