<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_withdrawal_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('admin_note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'status', 'created_at'], 'withdrawal_doctor_month_status_idx');
            $table->index(['status', 'created_at'], 'withdrawal_status_created_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_withdrawal_requests');
    }
};
