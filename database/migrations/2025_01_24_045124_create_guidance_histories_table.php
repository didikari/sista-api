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
        Schema::create('guidance_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guidance_id');
            $table->date('guidance_date');
            $table->text('notes')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->foreign('guidance_id')->references('id')->on('guidances')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guidance_histories');
    }
};
