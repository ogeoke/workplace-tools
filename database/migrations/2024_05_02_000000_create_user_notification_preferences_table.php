<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->boolean('in_app')->default(true);
            $table->boolean('whatsapp')->default(true);
            $table->boolean('email')->default(true);
            $table->boolean('push')->default(true);
            $table->boolean('quiet_hours_enabled')->default(false);
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->boolean('task_notifications')->default(true);
            $table->boolean('project_notifications')->default(true);
            $table->boolean('invoice_notifications')->default(true);
            $table->boolean('message_notifications')->default(true);
            $table->boolean('follow_up_notifications')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
