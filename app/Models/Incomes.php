<?php

namespace App\Models;

use App\Traits\RefreshTable;
use Illuminate\Database\Eloquent\Model;

class Incomes extends Model
{
    protected $table = 'incomes';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
      'income_id',
      'number',
      'date',
      'last_change_date',
      'supplier_article',
      'tech_size',
      'barcode',
      'quantity',
      'total_price',
      'date_close',
      'warehouse_name',
      'nm_id'
    ];
    protected $casts = [
        'income_id' => 'integer',
        'number' => 'string',
        'date' => 'date',
        'last_change_date' => 'date',
        'supplier_article' => 'string',
        'tech_size' => 'string',
        'barcode' => 'integer',
        'quantity' => 'integer',
        'total_price' => 'string',
        'date_close' => 'date',
        'warehouse_name' => 'string',
        'nm_id' => 'integer'
    ];

    protected $hidden = [
        'created_at','updated_at'
    ];
    use RefreshTable;
}
