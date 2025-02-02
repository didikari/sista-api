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
        Schema::create('grade_weights', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('role', ['supervisor', 'examiner']);
            $table->decimal('a1_weight', 5, 2);
            $table->decimal('a2_weight', 5, 2);
            $table->decimal('a3_weight', 5, 2);
            $table->decimal('a4_weight', 5, 2);
            $table->decimal('a5_weight', 5, 2);
            $table->decimal('a6_weight', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_weights');
    }
};
