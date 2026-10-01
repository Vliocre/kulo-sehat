<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('premium_until')->nullable()->after('doctor_verified_at');
        });

        Schema::table('keluhans', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->string('jenis', 20)->default('gratis')->after('doctor_id')->index();
            $table->string('premium_status', 20)->nullable()->after('status')->index();
            $table->timestamp('premium_started_at')->nullable()->after('premium_status');
            $table->timestamp('premium_ended_at')->nullable()->after('premium_started_at');
            $table->index(['doctor_id', 'jenis', 'premium_status'], 'keluhans_doctor_premium_index');
        });
    }

    public function down(): void
    {
        Schema::table('keluhans', function (Blueprint $table) {
            $table->dropIndex('keluhans_doctor_premium_index');
            $table->dropForeign(['doctor_id']);
            $table->dropColumn([
                'doctor_id',
                'jenis',
                'premium_status',
                'premium_started_at',
                'premium_ended_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('premium_until');
        });
    }
};
