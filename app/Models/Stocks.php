<?php /** @noinspection PhpMultipleClassesDeclarationsInOneFile */

namespace App\Models;

use App\Traits\RefreshTable;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    protected $table = 'stocks';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'date',
        'last_change_date',
        'supplier_article',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'in_way_to_client',
        'in_way_from_client',
        'nm_id',
        'subject',
        'category',
        'brand',
        'sc_code',
        'price',
        'discount',
    ];

    protected $casts = [
        'date' => 'date',
        'last_change_date' => 'date',
        'supplier_article' => 'string',
        'tech_size' => 'string',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'quantity_full' => 'integer',
        'in_way_to_client' => 'boolean',
        'in_way_from_client' => 'boolean',
        'nm_id' => 'integer',
        'subject' => 'string',
        'category' => 'string',
        'brand' => 'string',
        'sc_code' => 'integer',
        'price' => 'string',
        'discount' => 'string',
    ];

    protected $hidden = [
      'created_at','updated_at'
    ];

    use RefreshTable;
}
