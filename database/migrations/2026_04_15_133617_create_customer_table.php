<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('alamat')->nullable();
            $table->string('kode_provinsi', 2)->nullable();
            $table->string('kode_kota', 4)->nullable();
            $table->string('kode_kecamatan', 7)->nullable();
            $table->string('kode_kelurahan', 10)->nullable();
            $table->longText('foto_blob')->nullable()->comment('Base64 encoded foto dari kamera');
            $table->string('foto_path')->nullable()->comment('Path file foto yang disimpan di storage');
            $table->timestamps();

            $table->foreign('kode_provinsi')->references('kode')->on('provinsi')->nullOnDelete();
            $table->foreign('kode_kota')->references('kode')->on('kota')->nullOnDelete();
            $table->foreign('kode_kecamatan')->references('kode')->on('kecamatan')->nullOnDelete();
            $table->foreign('kode_kelurahan')->references('kode')->on('kelurahan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
