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
        Schema::create('product_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_receipt_id')->constrained('product_receipt')->onDelete('cascade'); // Ràng buộc khóa ngoại với bảng `product_receipt`
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Tên sản phẩm
            $table->integer('quantity'); // Số lượng
            $table->double('price',10,2);
            $table->double('total',10,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_receipt_items');
    }
};
