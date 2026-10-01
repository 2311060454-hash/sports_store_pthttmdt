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
            if (!Schema::hasColumn('orders', 'to_district_id')) {
                $table->integer('to_district_id')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'to_ward_code')) {
                $table->string('to_ward_code')->nullable()->after('to_district_id');
            }
            if (!Schema::hasColumn('orders', 'ghn_total_fee')) {
                $table->integer('ghn_total_fee')->default(0)->after('ghn_fee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['to_district_id', 'to_ward_code', 'ghn_total_fee']);
        });
    }
};
