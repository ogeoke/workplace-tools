<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('worklogs', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('hours_spent');
            $table->text('description')->nullable();
            $table->date('work_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['task_id', 'user_id']);
            $table->index('work_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worklogs');
    }
};
