<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrar dados existentes da tabela users para addresses
        DB::table('users')->whereNotNull('address')
            ->orderBy('id')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    DB::table('addresses')->insert([
                        'user_id' => $user->id,
                        'street' => $user->address ?? '',
                        'neighborhood' => 'Centro', // Campo padrão pois não existe no users
                        'number_house' => $user->number_house ?? 0,
                        'complement' => $user->complement ?? '',
                        'zip_code' => $user->zip_code ?? '',
                        'city' => 'Cidade', // Campo padrão pois não existe no users
                        'state' => 'Estado', // Campo padrão pois não existe no users
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

        // Remover colunas duplicadas da tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'zip_code', 'number_house', 'complement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recriar colunas na tabela users
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->integer('number_house')->nullable();
            $table->string('complement')->nullable();
        });

        // Migrar dados de volta
        DB::table('addresses')->orderBy('id')->chunk(100, function ($addresses) {
            foreach ($addresses as $address) {
                DB::table('users')
                    ->where('id', $address->user_id)
                    ->update([
                        'address' => $address->street,
                        'zip_code' => $address->zip_code,
                        'number_house' => $address->number_house,
                        'complement' => $address->complement,
                    ]);
            }
        });

        // Deletar registros da tabela addresses
        DB::table('addresses')->truncate();
    }
};
