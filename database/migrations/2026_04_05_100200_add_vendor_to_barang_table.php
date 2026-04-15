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
        Schema::table('barang', function (Blueprint $table) {
            $table->unsignedInteger('id_vendor')->nullable()->after('harga');
            $table->string('path_gambar', 255)->nullable()->after('id_vendor');

            $table->foreign('id_vendor')
                ->references('id_vendor')
                ->on('vendor')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropForeign(['id_vendor']);
            $table->dropColumn(['id_vendor', 'path_gambar']);
        });
    }
};
