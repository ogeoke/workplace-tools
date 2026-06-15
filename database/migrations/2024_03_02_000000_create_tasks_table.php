<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('epic_id')->nullable();
            $table->unsignedBigInteger('sprint_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // For subtasks
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('key', 20)->unique();
            $table->enum('status', ['backlog', 'todo', 'in_progress', 'review', 'testing', 'blocked', 'done'])->default('backlog');
            $table->enum('priority', ['lowest', 'low', 'medium', 'high', 'highest'])->default('medium');
            $table->enum('type', ['task', 'subtask', 'story', 'bug', 'feature', 'chore'])->default('task');
            $table->integer('estimate')->nullable(); // Story points or hours
            $table->integer('time_spent')->default(0);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->index(['project_id', 'status', 'priority']);
            $table->index(['sprint_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
