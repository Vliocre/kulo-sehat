<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category_slug');
            $table->string('topic_slug');
            $table->timestamps();

            $table->unique(['user_id', 'category_slug', 'topic_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_bookmarks');
    }
};
