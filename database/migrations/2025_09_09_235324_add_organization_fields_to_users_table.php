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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['individual', 'organization'])->default('individual')->after('email');
            $table->string('cnpj')->nullable()->after('phone');
            $table->string('organization_name')->nullable()->after('cnpj');
            $table->string('responsible_name')->nullable()->after('organization_name');
            $table->text('mission_statement')->nullable()->after('responsible_name');
            $table->string('website')->nullable()->after('mission_statement');
            $table->json('social_media')->nullable()->after('website');
            $table->boolean('verified')->default(false)->after('social_media');
            $table->timestamp('verified_at')->nullable()->after('verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'cnpj',
                'organization_name',
                'responsible_name',
                'mission_statement',
                'website',
                'social_media',
                'verified',
                'verified_at',
            ]);
        });
    }
};
