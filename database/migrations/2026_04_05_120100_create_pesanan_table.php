<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('idpesanan');
            $table->string('nama', 255);
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->integer('metode_bayar')->comment('1=VA, 2=QRIS');
            $table->smallInteger('status_bayar')->default(0)->comment('0=pending, 1=lunas, 2=gagal');
            // Tambahan untuk integrasi payment gateway
            $table->string('payment_reference', 100)->nullable();
            $table->string('payment_channel', 50)->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
