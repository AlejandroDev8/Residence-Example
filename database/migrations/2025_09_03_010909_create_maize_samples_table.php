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
        Schema::create('maize_samples', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreign('user_id')->constrained()->onDelete('cascade');
            $table->foreign('farmer_id')->constrained()->onDelete('cascade');
            $table->foreignId('municipality_id')->constrained()->onDelete('cascade');
            $table->foreignId('locality_id')->constrained()->onDelete('cascade');

            // Sample MetaData
            $table->string('code')->nullable();
            $table->unsignedInteger('sample_number');
            $table->date('collection_date')->nullable();

            // Geographical Data
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();

            // Optional
            $table->string('variety_name')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['municipality_id', 'locality_id', 'sample_number'], 'mx_sample_unique_idx');
            $table->index(['user_id', 'collection_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maize_samples');
    }
};
