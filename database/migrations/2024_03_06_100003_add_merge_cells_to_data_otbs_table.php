<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMergeCellsToDataOtbsTable extends Migration
{
    public function up()
    {
        Schema::table('data_otbs', function (Blueprint $table) {
            $table->longText('merge_cells')->nullable(); // Tambahkan kolom ini
        });
    }

    public function down()
    {
        Schema::table('data_otbs', function (Blueprint $table) {
            $table->dropColumn('merge_cells'); // Hapus kolom jika migration di-rollback
        });
    }
}
