<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_tracker', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('client_id');
            $table->enum('status', ['lead', 'contacted', 'proposal_sent', 'negotiation', 'active', 'inactive', 'lost'])->default('lead');
            $table->unsignedBigInteger('account_manager_id')->nullable();
            $table->json('activity_history')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->date('expected_closure_date')->nullable();
            $table->text('closure_notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('account_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['organization_id', 'status']);
            $table->index('client_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_tracker');
    }
};
