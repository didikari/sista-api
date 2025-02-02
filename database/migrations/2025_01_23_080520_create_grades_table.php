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
        Schema::create('grades', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('gradable_id');
            $table->string('gradable_type');
            $table->uuid('lecturer_id');
            $table->foreign('lecturer_id')->references('id')->on('lecturers')->onDelete('cascade');
            $table->enum('role_in_activity', ['supervisor', 'examiner']);
            $table->decimal('a1', 5, 2)->nullable();
            $table->decimal('a2', 5, 2)->nullable();
            $table->decimal('a3', 5, 2)->nullable();
            $table->decimal('a4', 5, 2)->nullable();
            $table->decimal('a5', 5, 2)->nullable();
            $table->decimal('a6', 5, 2)->nullable();
            $table->enum('status', ['pending', 'finalized'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
