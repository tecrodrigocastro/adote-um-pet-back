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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->enum('type', ['dog', 'cat', 'rabbit', 'bird'])->default('cat');
            $table->enum('gender',['female', 'male', 'unknown'])->default('male');
            $table->enum('size', ['small', 'medium', 'large', 'unknown'])->default('unknown');
            $table->date('birth_date')->nullable();
            $table->string('breed')->nullable();
            $table->string('color')->nullable();
            $table->string('address');
            $table->longText('description');
            $table->longText('photos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
