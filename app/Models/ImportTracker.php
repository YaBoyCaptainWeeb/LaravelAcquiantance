<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $tracker_id
 * @property string $entity
 * @property int $total_pages
 * @property int $processed_pages
 * @property string $status
 * @property array|null $failed_pages
 */
class ImportTracker extends Model
{
    protected $fillable = [
        'tracker_id',
        'entity',
        'total_pages',
        'processed_pages',
        'status',
        'failed_pages',
    ];

    protected $casts = [
        'failed_pages' => 'array',
    ];
}
