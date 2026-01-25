<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_bulanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('pembelajaran_id')->constrained('pembelajarans')->onDelete('cascade');
            $table->tinyInteger('bulan');
            $table->tinyInteger('semester');
            $table->year('tahun');
            $table->bigInteger('nilai');
            $table->text('deskripsi_capaian_tinggi')->nullable();
            $table->text('deskripsi_capaian_rendah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nilai_bulanans');
    }
};