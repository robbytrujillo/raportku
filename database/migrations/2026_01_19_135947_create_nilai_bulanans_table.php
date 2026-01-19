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

            $table->foreignId('siswa_id')
          ->constrained('siswas')
          ->cascadeOnDelete();

            $table->foreignId('pembelajaran_id')
                ->constrained('pembelajarans')
                ->cascadeOnDelete();

            $table->tinyInteger('bulan');      // 1–12
            $table->tinyInteger('semester');   // 1 / 2
            $table->year('tahun');

            $table->integer('nilai')->nullable();
            $table->text('capaian_tp_optimal')->nullable();
            $table->text('capaian_tp_kurang')->nullable();
            $table->text('deskripsi')->nullable();

            $table->timestamps();

            $table->unique(
                ['siswa_id', 'pembelajaran_id', 'bulan', 'semester', 'tahun'],
                'nilai_bulanan_unique'
            );
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