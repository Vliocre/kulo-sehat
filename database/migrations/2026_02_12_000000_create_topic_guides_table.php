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
        Schema::create('topic_guides', function (Blueprint $table) {
            $table->id();
            $table->string('category_slug', 50);
            $table->string('topic_slug', 120);
            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('symptoms')->nullable();
            $table->json('care')->nullable();
            $table->json('prevention')->nullable();
            $table->json('danger_signs')->nullable();
            $table->string('palette')->nullable();
            $table->timestamps();

            $table->unique(['category_slug', 'topic_slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_guides');
    }
};
