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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string("first_name");
            $table->string("last_name");
            $table->string("student_name");
            $table->string("student_photo");
            $table->unsignedBigInteger("location_id");
            $table->unsignedBigInteger("madrassa_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('madrassa_id')->references('id')->on('madrassas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
