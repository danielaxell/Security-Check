<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoPaketColumn extends Migration
{
    public function up()
    {
        Schema::table('TB_PAKETMASUK', function (Blueprint $table) {
            $table->string('foto_paket')->nullable()->after('keterangan');
        });

        Schema::table('TB_PAKETKELUAR', function (Blueprint $table) {
            $table->string('foto_paket')->nullable()->after('keterangan');
        });
    }

    public function down()
    {
        Schema::table('TB_PAKETMASUK', function (Blueprint $table) {
            $table->dropColumn('foto_paket');
        });

        Schema::table('TB_PAKETKELUAR', function (Blueprint $table) {
            $table->dropColumn('foto_paket');
        });
    }
} 