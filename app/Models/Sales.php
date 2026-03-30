<?php

namespace App\Models;

use App\Traits\RefreshTable;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'g_number',
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'total_price',
        'discount_percent',
        'is_supply',
        'is_realisation',
        'promo_code_discount',
        'warehouse_name',
        'country_name',
        'oblast_okrug_name',
        'region_name',
        'income_id',
        'sale_id',
        'odid',
        'spp',
        'for_pay',
        'finished_price',
        'price_with_disc',
        'nm_id',
        'subject',
        'category',
        'brand',
        'is_storno'
    ];
    protected $casts = [
        'g_number' => 'string',
        'date' => 'date',
        'last_change_date' => 'date',
        'supplier_article' => 'string',
        'tech_size' => 'string',
        'barcode' => 'integer',
        'total_price' => 'string',
        'discount_percent' => 'string',
        'is_supply' => 'boolean',
        'is_realisation' => 'boolean',
        'promo_code_discount' => 'integer',
        'warehouse_name' => 'string',
        'country_name' => 'string',
        'oblast_okrug_name' => 'string',
        'region_name' => 'string',
        'income_id' => 'integer',
        'sale_id' => 'string',
        'odid' => 'string',
        'spp' => 'string',
        'for_pay' => 'string',
        'finished_price' => 'string',
        'price_with_disc' => 'string',
        'nm_id' => 'integer',
        'subject' => 'string',
        'category' => 'string',
        'brand' => 'string',
        'is_storno' => 'boolean',
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
    use RefreshTable;
}
