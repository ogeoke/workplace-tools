<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['public', 'private', 'team', 'department', 'project'])->default('public');
            $table->boolean('is_archived')->default(false);
            $table->boolean('is_general')->default(false);
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'type', 'is_archived']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
