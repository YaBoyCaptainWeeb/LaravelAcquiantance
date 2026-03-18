<?php

namespace App\Models;

use App\Traits\RefreshTable;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = [
        "g_number",
        "date",
        "last_change_date",
        "supplier_article",
        "tech_size",
        "barcode",
        "total_price",
        "discount_percent",
        "warehouse_name",
        "oblast",
        "income_id",
        "odid",
        "nm_id",
        "subject",
        "category",
        "brand",
        "is_cancel",
        "cancel_dt",
    ];

    protected $casts = [
        "g_number" => "string",
        "date" => "date",
        "last_change_date" => "date",
        "supplier_article" => "string",
        "tech_size" => "string",
        "barcode" => "integer",
        "total_price" => "string",
        "discount_percent" => "integer",
        "warehouse_name" => "string",
        "oblast" => "string",
        "income_id" => "integer",
        "odid" => "string",
        "nm_id" => "integer",
        "subject" => "string",
        "category" => "string",
        "brand" => "string",
        "is_cancel" => "boolean",
        "cancel_dt" => "date"
    ];

    use RefreshTable;
}
