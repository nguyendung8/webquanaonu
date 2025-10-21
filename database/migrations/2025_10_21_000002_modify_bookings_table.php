<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->string('payment_img')->nullable()->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->enum('payment_status', ['pending','paid','cancelled'])->default('pending')->after('amount');
            $table->dropColumn('payment_img');
        });
    }
};
