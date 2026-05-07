<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('doctor_idi_number')->nullable()->after('about');
            $table->string('doctor_str_file')->nullable()->after('doctor_idi_number');
            $table->string('doctor_sip_file')->nullable()->after('doctor_str_file');
            $table->enum('doctor_verification_status', ['not_required', 'pending', 'approved', 'rejected'])
                ->default('not_required')
                ->after('doctor_sip_file');
            $table->timestamp('doctor_verified_at')->nullable()->after('doctor_verification_status');
        });

        DB::table('users')
            ->where('role', 'dokter')
            ->update([
                'doctor_verification_status' => 'approved',
                'doctor_verified_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'doctor_idi_number',
                'doctor_str_file',
                'doctor_sip_file',
                'doctor_verification_status',
                'doctor_verified_at',
            ]);
        });
    }
};
