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
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('kode_piutang');
            $table->string('nomor_faktur');
            $table->string('nomor_order');
            $table->unsignedBigInteger('terms');
            $table->date('tanggal_transaction');
            $table->date('tanggal_jatuh_tempo');
            $table->decimal('jumlah_piutang', 13, 2);
            $table->decimal('sisa_piutang', 13, 2)->default(0);
            $table->string('status_pembayaran');
            $table->unsignedBigInteger('ppn')->nullable();
            $table->date('tanggal_lunas')->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutangs');
    }
};
