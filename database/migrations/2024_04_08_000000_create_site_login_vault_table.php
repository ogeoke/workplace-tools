<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_login_vault', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->string('site_name');
            $table->string('url')->nullable();
            $table->string('username')->nullable();
            $table->longText('password_encrypted'); // Encrypted
            $table->enum('access_level', ['super_admin', 'admin', 'user', 'limited'])->default('user');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->integer('accessed_by_count')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->index('organization_id');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_login_vault');
    }
};
