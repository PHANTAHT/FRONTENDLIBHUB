<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_tenggat')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'kembali', 'terlambat'])->default('dipinjam');
            $table->decimal('denda', 12, 2)->default(0);
            $table->decimal('biaya_sewa', 12, 2)->default(0);
            $table->decimal('deposit', 12, 2)->default(0);
            $table->decimal('deposit_dikembalikan', 12, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('loans'); }
};
