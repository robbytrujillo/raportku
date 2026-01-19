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
        Schema::table('nilai_akhirs', function (Blueprint $table) {
            //
            Schema::table('nilai_akhirs', function (Blueprint $table) {
            $table->tinyInteger('semester')->nullable()->after('pembelajaran_id');
            $table->tinyInteger('bulan')->nullable()->after('semester');
            $table->smallInteger('tahun')->after('bulan');
        });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_akhirs', function (Blueprint $table) {
            //
            $table->dropColumn(['semester', 'bulan', 'tahun']);
        });
    }
};