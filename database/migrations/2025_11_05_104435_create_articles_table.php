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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->longText('content');
        $table->string('image')->nullable(); // Path ke gambar
        $table->enum('status', ['published', 'draft'])->default('draft');

        // Relasi ke tabel users (penulis artikel)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Relasi ke tabel categories
        $table->foreignId('category_id')->constrained()->onDelete('cascade');

        $table->timestamps();
    });
}
};
