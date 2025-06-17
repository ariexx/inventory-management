<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->foreignId('pesanan_id')->nullable()->after('supplier_id')->constrained('pesanan_barang')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->dropColumn('pesanan_id');
        });
    }
};
