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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã voucher
            $table->enum('discount_type', ['amount', 'percent']);
            $table->decimal('discount_amount', 8, 2)->nullable(); // Giá trị giảm giá cố định
            $table->integer('discount_percent')->nullable(); // Phần trăm giảm giá
            $table->decimal('min_order_value', 8, 2)->default(0); // Giá trị đơn hàng tối thiểu
            $table->integer('usage_limit')->nullable(); // Giới hạn số lần sử dụng
            $table->integer('used')->default(0); // Số lần đã sử dụng
            $table->dateTime('start_date')->nullable(); // Ngày bắt đầu áp dụng
            $table->dateTime('end_date')->nullable(); // Ngày kết thúc áp dụng
            $table->boolean('status')->default(true); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
