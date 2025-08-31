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
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->char('cve_mun', 3);
            $table->char('cve_geo', 5);
            $table->string('name', 150);
            $table->timestamps();

            $table->unique(['state_id', 'cve_mun']);
            $table->index(['state_id', 'cve_mun']);
            $table->index('cve_geo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
