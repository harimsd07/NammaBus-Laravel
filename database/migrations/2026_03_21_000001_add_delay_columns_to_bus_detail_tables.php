<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bus_detail_tables', function (Blueprint $table) {
            // Nullable — only set when driver reports a delay
            $table->unsignedInteger('delay_minutes')
                  ->nullable()
                  ->after('longitude')
                  ->comment('Delay in minutes reported by driver');

            $table->string('delay_reason', 255)
                  ->nullable()
                  ->after('delay_minutes')
                  ->comment('Reason for delay e.g. Traffic, Breakdown');

            $table->timestamp('delay_reported_at')
                  ->nullable()
                  ->after('delay_reason')
                  ->comment('When the delay was reported');
        });
    }

    public function down(): void
    {
        Schema::table('bus_detail_tables', function (Blueprint $table) {
            $table->dropColumn([
                'delay_minutes',
                'delay_reason',
                'delay_reported_at',
            ]);
        });
    }
};
