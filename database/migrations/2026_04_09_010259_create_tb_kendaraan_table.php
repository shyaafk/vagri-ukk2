<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            
            // 🔥 TAMBAHAN
            $table->unsignedBigInteger('id_user');

            $table->string('plat_nomor');
            $table->enum('jenis_kendaraan', ['motor','mobil','lainnya']);
            $table->string('warna');
            $table->string('pemilik')->nullable();
            $table->timestamps();

            // 🔥 RELASI KE tb_user
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('tb_user')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kendaraan');
    }
};