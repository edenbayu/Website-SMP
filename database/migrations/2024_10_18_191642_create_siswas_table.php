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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('no_pendaftaran')->unique(); // Corrected unique method call
            $table->integer('no_un')->nullable(); // Marked as nullable
            $table->integer('no_peserta')->nullable(); // Marked as nullable
            $table->integer('nisn')->nullable(); // Marked as nullable
            $table->integer('no_formulir')->nullable(); // Marked as nullable
            $table->string('nama')->nullable(); // Marked as nullable
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(); // Marked as nullable
            $table->string('tempat_lahir')->nullable(); // Marked as nullable
            $table->date('tanggal_lahir')->nullable(); // Marked as nullable
            $table->string('alamat_lengkap')->nullable(); // Marked as nullable
            $table->string('provinsi')->nullable(); // Marked as nullable
            $table->string('kota_kabupaten')->nullable(); // Marked as nullable
            $table->string('kecamatan')->nullable(); // Marked as nullable
            $table->string('kelurahan')->nullable(); // Marked as nullable
            $table->integer('no_rw')->nullable(); // Marked as nullable
            $table->integer('no_rt')->nullable(); // Marked as nullable
            $table->float('lintang')->nullable(); // Marked as nullable
            $table->float('bujur')->nullable(); // Marked as nullable
            $table->string('asal_sekolah')->nullable(); // Marked as nullable
            $table->integer('npsn_asal_sekolah')->nullable(); // Marked as nullable
            $table->string('status_kelulusan')->nullable(); // Marked as nullable
            $table->integer('tahun_kelulusan')->nullable(); // Marked as nullable
            $table->string('kapasitas')->nullable(); // Marked as nullable
            $table->string('radius')->nullable(); // Marked as nullable
            $table->string('usia')->nullable(); // Marked as nullable
            $table->string('waktu_pengajuan')->nullable(); // Marked as nullable
            $table->string('lokasi_pendaftaran')->nullable(); // Marked as nullable
            $table->string('lapor_diri')->nullable(); // Marked as nullable
            $table->string('pilihan_1')->nullable(); // Marked as nullable
            $table->foreignId('id_user')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
