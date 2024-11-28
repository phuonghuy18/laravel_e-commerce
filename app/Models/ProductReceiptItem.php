<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReceiptItem extends Model
{
    protected $table = 'product_receipt_items';
    protected $fillable = [
        'product_receipt_id',
        'product_id',
        'quantity',
        'product_name',
        'price',
        'total'
    ]; // Đảm bảo đúng tên bảng
    use HasFactory;
    public function productReceipt()
    {
        return $this->belongsTo(ProductReceipt::class);
    }
}
