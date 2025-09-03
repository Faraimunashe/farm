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
        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('insurer_id');
            $table->string('name');
            $table->text('description');
            $table->string('coverage_type');
            $table->decimal('coverage_percentage', 5, 2);
            $table->decimal('premium_rate', 6, 4);
            $table->decimal('min_coverage', 10, 2);
            $table->decimal('max_coverage', 10, 2);
            $table->integer('duration_months');
            $table->decimal('deductible_amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->text('terms_conditions')->nullable();
            $table->text('exclusions')->nullable();
            $table->timestamps();

            $table->foreign('insurer_id')->references('id')->on('insurers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                    Schema::dropIfExists('insurance_plans');
    }
};
