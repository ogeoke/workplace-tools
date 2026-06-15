<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('client_id');
            $table->date('follow_up_date');
            $table->unsignedBigInteger('assigned_to');
            $table->enum('communication_channel', ['phone', 'email', 'sms', 'whatsapp', 'in_person'])->default('email');
            $table->enum('status', ['pending', 'completed', 'rescheduled', 'cancelled'])->default('pending');
            $table->text('next_action')->nullable();
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('restrict');
            $table->index(['organization_id', 'follow_up_date', 'status']);
            $table->index('client_id');
            $table->index('follow_up_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_follow_ups');
    }
};
