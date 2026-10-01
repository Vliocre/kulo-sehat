<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluhan_premiums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluhan_id')->constrained('keluhans')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->text('pesan');
            $table->string('gambar')->nullable();
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();

            $table->index(['keluhan_id', 'created_at']);
            $table->index(['sender_id', 'dibaca_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluhan_premiums');
    }
};
