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
        Schema::create('organization_volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['admin', 'manager', 'volunteer'])->default('volunteer');
            $table->json('permissions')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('joined_at');
            $table->timestamps();

            // Único constraint para evitar duplicatas
            $table->unique(['organization_id', 'volunteer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_volunteers');
    }
};
