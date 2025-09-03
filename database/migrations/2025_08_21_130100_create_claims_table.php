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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insurance_id');
            $table->bigInteger('user_id');
            $table->bigInteger('farm_id');
            $table->decimal('claim_amount', 10, 2);
            $table->date('incident_date');
            $table->text('description');
            $table->json('evidence_files')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('approved_amount', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->text('investigation_notes')->nullable();
            $table->bigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('insurance_id')->references('id')->on('insurances')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['insurance_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['farm_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
