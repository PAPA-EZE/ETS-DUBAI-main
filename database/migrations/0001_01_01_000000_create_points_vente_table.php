<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_vente', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->enum('type', ['central', 'vente'])->default('vente');
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });

        // Insérer les 4 points de vente
        DB::table('points_vente')->insert([
            [
                'nom' => 'Dubai Magasin',
                'code' => 'DXB-MAG',
                'adresse' => 'Sangmelima, Cameroun',
                'type' => 'central',
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Dubai Cave',
                'code' => 'DXB-CAVE',
                'adresse' => 'Sangmelima, Cameroun',
                'type' => 'vente',
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Dubai Lounge Plus',
                'code' => 'DXB-LOUNGE',
                'adresse' => 'Sangmelima, Cameroun',
                'type' => 'vente',
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Dubai Meyomadjom',
                'code' => 'DXB-MEYO',
                'adresse' => 'Meyomadjom, Cameroun',
                'type' => 'vente',
                'actif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('points_vente');
    }
};
