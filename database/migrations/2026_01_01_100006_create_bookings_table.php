<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->constrained('books')->cascadeOnDelete();
            $table->date('tanggal_booking');
            $table->date('tanggal_expired');
            $table->enum('status', ['pending_payment', 'reserved', 'completed', 'cancelled', 'expired'])->default('reserved');
            $table->date('tanggal_rencana_kembali')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};
