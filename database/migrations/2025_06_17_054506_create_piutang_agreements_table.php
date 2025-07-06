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
        Schema::create('piutang_agreements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->foreignId('piutang_id')->constrained()->onDelete('cascade');
            $table->string('agreement_number');        // nomor dokumen, misal: AG-001/2025
            $table->string('agreement_lampiran');        // nomor dokumen, misal: AG-001/2025
            $table->string('agreement_perihal');        // nomor dokumen, misal: AG-001/2025

            // Pihak pemberi (misalnya admin perusahaan)
            $table->string('leader_company');           // PT. Tayoh Sarana Sukses
            $table->string('leader_name');              // Mahesa Adi Kusuma
            $table->string('leader_position');          // Accounting & Finance Manager

            // Pihak penerima (customer)
            $table->string('borrower_company'); // Budi Hartono
            $table->string('borrower_name'); // Budi Hartono
            $table->text('borrower_address')->nullable(); // alamat 
            $table->string('borrower_position')->nullable(); // bisa kosong kalau bukan perusahaan

            $table->date('agreement_date');            // tanggal dibuat
            $table->text('content');                   // isi perjanjian
            $table->string('generated_pdf')->nullable(); // path PDF kalau digenerate otomatis
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang_agreements');
    }
};
