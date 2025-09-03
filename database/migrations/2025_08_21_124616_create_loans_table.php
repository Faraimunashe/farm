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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('lender_id');
            $table->bigInteger('farm_id')->nullable();
            $table->decimal('amount');
            $table->text('purpose');
            $table->string('status');
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->integer('term_months')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lender_id')->references('id')->on('lenders')->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
