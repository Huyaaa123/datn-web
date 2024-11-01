<?php

use App\Models\OrderStatus;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(OrderStatus::class)->constrained();
            $table->decimal('total_amount', 15, 2); // Tổng tiền
            $table->date('order_date')->nullable(); // Ngày đặt hàng
            $table->string('telephone')->nullable();
            $table->string('shipping_address')->nullable(); // Địa chỉ giao hàng
            $table->string('payment_method'); // Phương thức thanh toán
            $table->text('notes')->nullable(); // Ghi chú
            $table->text('cancel')->nullable(); // Huy đơn hang neu co
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
