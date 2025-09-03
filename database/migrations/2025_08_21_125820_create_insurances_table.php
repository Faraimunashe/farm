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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('insurer_id');
            $table->bigInteger('insurance_plan_id');
            $table->bigInteger('farm_id');
            $table->string('policy_number');
            $table->decimal('coverage_amount', 10, 2);
            $table->decimal('premium_amount', 10, 2);
            $table->string('coverage_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->date('renewal_date')->nullable();
            $table->integer('claims_count')->default(0);
            $table->date('last_claim_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('insurer_id')->references('id')->on('insurers')->onDelete('cascade');
            $table->foreign('insurance_plan_id')->references('id')->on('insurance_plans')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                    Schema::dropIfExists('insurances');
    }
};
