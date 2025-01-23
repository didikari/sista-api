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
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->enum('activity_type', ['seminar', 'pre_seminar', 'exam']);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
