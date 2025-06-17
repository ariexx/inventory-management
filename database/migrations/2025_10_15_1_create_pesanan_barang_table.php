<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pesanan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pesanan')->unique();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained('supplier')->onDelete('restrict');
            $table->integer('jumlah');
            $table->date('tanggal_pemesanan');
            $table->date('tanggal_pengiriman_diharapkan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'received', 'cancelled'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pesanan_barang');
    }
};
