<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceipt extends Model
{
    protected $table = 'product_receipt'; // Đặt tên bảng là 'product_receipt'
    use HasFactory;
    public function items()
    {
        return $this->hasMany(ProductReceiptItem::class);
    }
}
