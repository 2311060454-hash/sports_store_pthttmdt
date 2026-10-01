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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ghn_order_code')->nullable()->after('status')->index();
            $table->string('ghn_status')->nullable()->after('ghn_order_code');
            $table->decimal('ghn_fee', 12, 2)->nullable()->after('ghn_status');
            $table->timestamp('ghn_expected_delivery')->nullable()->after('ghn_fee');
            $table->json('ghn_tracking_log')->nullable()->after('ghn_expected_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ghn_order_code', 'ghn_status', 'ghn_fee', 'ghn_expected_delivery', 'ghn_tracking_log']);
        });
    }
};
