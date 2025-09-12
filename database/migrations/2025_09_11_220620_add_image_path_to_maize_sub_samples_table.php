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
        Schema::table('maize_sub_samples', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('volumen_grano_50_ml');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maize_sub_samples', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
