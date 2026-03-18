<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @mixin Model
 */
trait RefreshTable
{
    /**
     * @param iterable $data
     * @param int $batchSize
     * @return int
     * @throws Throwable
     */
    public static function RefreshTable(iterable $data, int $batchSize = 500): int
    {
        $table = (new static)->getTable();
        return DB::transaction(function () use ($data, $batchSize, $table) {

            DB::table($table)->delete();

            $batch = [];
            $count = 0;

            foreach ($data as $item) {
                $batch[] = (array)$item;
                $count++;

                if (count($batch) >= $batchSize) {
                    DB::table($table)->insert($batch);
                    $batch = [];
                }
            }
            if (!empty($batch)) {
                DB::table($table)->insert($batch);
            }

            return $count;
        });
    }
}
