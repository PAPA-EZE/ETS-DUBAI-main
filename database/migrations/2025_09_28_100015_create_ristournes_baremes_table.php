<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ristournes_baremes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ristourne_id')->constrained('ristournes')->onDelete('cascade');
            $table->decimal('seuil_min', 15, 2);
            $table->decimal('seuil_max', 15, 2)->nullable();
            $table->decimal('pourcentage', 5, 2);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ristournes_baremes');
    }
};
