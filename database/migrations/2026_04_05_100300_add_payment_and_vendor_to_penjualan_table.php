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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id_penjualan');
            $table->string('nama_customer', 255)->nullable()->after('user_id');
            $table->unsignedInteger('id_vendor')->nullable()->after('nama_customer');

            $table->string('metode_bayar', 30)->nullable()->after('total');
            $table->string('payment_channel', 50)->nullable()->after('metode_bayar');
            $table->string('payment_reference', 100)->nullable()->after('payment_channel');
            $table->string('status_bayar', 20)->default('pending')->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('status_bayar');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['id_vendor']);

            $table->dropColumn([
                'user_id',
                'nama_customer',
                'id_vendor',
                'metode_bayar',
                'payment_channel',
                'payment_reference',
                'status_bayar',
                'paid_at',
            ]);
        });
    }
};
