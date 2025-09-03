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
        Schema::create('maize_sub_samples', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('maize_sample_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('subsample_number');

            // Categorical
            $table->string('color_grano')->nullable();
            $table->string('color_olote')->nullable();
            $table->string('tipo_grano')->nullable();
            $table->string('forma_corona_grano')->nullable();
            $table->string('color_dorsal_grano')->nullable();
            $table->string('color_endospermo_grano')->nullable();
            $table->string('arreglo_hileras_grano')->nullable();

            // Metrics
            $table->decimal('diametro_mazorca_mm', 6, 2)->nullable();
            $table->decimal('largo_mazorca_mm', 6, 2)->nullable();
            $table->decimal('peso_mazorca_g', 7, 2)->nullable();
            $table->decimal('peso_grano_50_g', 7, 2)->nullable();
            $table->unsignedInteger('num_hileras')->nullable();
            $table->unsignedInteger('num_granos_por_hilera')->nullable();
            $table->decimal('grosor_grano_mm', 6, 2)->nullable();
            $table->decimal('ancho_grano_mm', 6, 2)->nullable();
            $table->decimal('longitud_grano_mm', 6, 2)->nullable();
            $table->decimal('indice_lgr_agr', 6, 3)->nullable();
            $table->decimal('volumen_grano_50_ml', 7, 2)->nullable();

            $table->timestamps();

            $table->unique(['maize_sample_id', 'subsample_number'], 'mx_subsample_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maize_sub_samples');
    }
};
