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
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade');
            $table->char('cve_loc', 4); // 0001..9999
            $table->char('cve_geo', 9); // cve_ent + cve_mun + cve_loc
            $table->string('name');
            $table->boolean('urban_area')->nullable(); // true=Urbano, false=Rural
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->timestamps();

            $table->unique(['municipality_id', 'cve_loc']);
            $table->index(['municipality_id', 'name']);
            $table->index('cve_geo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localities');
    }
};
