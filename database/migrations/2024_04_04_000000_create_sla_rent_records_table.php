<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sla_rent_records', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->enum('type', ['sla', 'rent'])->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->date('renewal_date')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->unsignedBigInteger('responsible_person_id')->nullable();
            $table->integer('reminder_days')->default(7);
            $table->longText('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('responsible_person_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['organization_id', 'type', 'renewal_date']);
            $table->index('company_id');
            $table->index('renewal_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_rent_records');
    }
};
