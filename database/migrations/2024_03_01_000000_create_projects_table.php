<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->string('name');
            $table->string('key', 10)->unique();
            $table->longText('description')->nullable();
            $table->enum('status', ['active', 'archived', 'closed'])->default('active');
            $table->enum('type', ['software', 'service', 'product', 'internal'])->default('software');
            $table->enum('visibility', ['public', 'private'])->default('private');
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('color', 10)->nullable();
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('lead_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['organization_id', 'status']);
            $table->index(['team_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
