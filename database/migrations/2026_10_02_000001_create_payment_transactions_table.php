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
        // 1. Bảng payment_transactions (Lab Orders - MoMo)
        if (!Schema::hasTable('payment_transactions')) {
            Schema::create('payment_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->string('gateway'); // phân biệt các cổng thanh toán: momo, cod, vnpay, etc.
                $table->string('gateway_order_id')->nullable()->index(); // ID đơn hàng từ cổng thanh toán
                $table->string('transaction_id')->nullable()->index(); // ID giao dịch từ cổng thanh toán (transId)
                $table->decimal('amount', 15, 2); // số tiền thanh toán
                $table->string('status')->default('pending'); // pending, initiated, paid, failed, canceled
                $table->integer('result_code')->nullable(); // mã kết quả từ cổng thanh toán
                $table->string('message')->nullable(); // thông điệp từ cổng thanh toán
                $table->json('request_payload')->nullable(); // dữ liệu yêu cầu gửi đến cổng thanh toán
                $table->json('response_payload')->nullable(); // dữ liệu phản hồi từ cổng thanh toán
                $table->timestamp('paid_at')->nullable(); // thời gian thanh toán thành công
                $table->timestamps();

                $table->unique(['gateway', 'gateway_order_id']);
                $table->index(['order_id', 'status']);
            });
        }

        // 2. Thêm cột shipping_status vào bảng orders nếu chưa có
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'shipping_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('shipping_status')->nullable()->default('pending')->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'shipping_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('shipping_status');
            });
        }
    }
};
