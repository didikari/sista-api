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
        Schema::create('pre_seminars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->uuid('title_id');
            $table->foreign('title_id')->references('id')->on('titles')->onDelete('cascade');
            $table->uuid('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('lecturers')->onDelete('cascade');
            $table->uuid('examiner_id');
            $table->foreign('examiner_id')->references('id')->on('lecturers')->onDelete('cascade');
            $table->string('pre_seminar_file');
            $table->timestamp('seminar_date')->nullable();
            $table->timestamp('submission_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'scheduled', 'completed', 'canceled'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre_seminars');
    }
};
