<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('id_barang', 8)->primary();
            $table->string('nama', 50);
            $table->integer('harga');
            $table->timestamp('timestamp')->useCurrent();
        });

        // Pastikan trigger lama tidak ada
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang');

        // Trigger MySQL untuk auto-generate id_barang: yymmdd + 2 digit urutan harian
        DB::unprepared("
            CREATE TRIGGER trigger_id_barang
            BEFORE INSERT ON barang
            FOR EACH ROW
            BEGIN
                DECLARE nr INT DEFAULT 0;

                SELECT COUNT(*) + 1
                INTO nr
                FROM barang
                WHERE DATE(`timestamp`) = CURDATE();

                SET NEW.id_barang = CONCAT(
                    DATE_FORMAT(NOW(), '%y%m%d'),
                    LPAD(nr, 2, '0')
                );
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_id_barang');
        Schema::dropIfExists('barang');
    }
};