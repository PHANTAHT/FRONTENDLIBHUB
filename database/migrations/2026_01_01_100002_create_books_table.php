<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->integer('tahun_terbit')->nullable();
            $table->string('isbn')->nullable();
            $table->integer('stok')->default(0);
            $table->string('foto_sampul')->nullable();
            $table->text('sinopsis')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('books'); }
};
