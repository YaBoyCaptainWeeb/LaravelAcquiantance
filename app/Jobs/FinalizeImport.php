<?php

namespace App\Jobs;

use App\Models\ImportTracker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class FinalizeImport implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected string $trackerId;
    protected string $modelClass;

    public function __construct(string $trackerId, string $modelClass)
    {
        $this->trackerId = $trackerId;
        $this->modelClass = $modelClass;
    }

    public function handle(): void
    {
        $tracker = ImportTracker::query()
            ->where('tracker_id', $this->trackerId)
            ->firstOrFail();

        $total = Redis::client()->get("import:$this->trackerId:total");
        $processed = Redis::client()->get("import:$this->trackerId:processed");

        if ($total === null)
        {
            $total = $tracker->total_pages;
            $processed = $tracker->processed_pages;
        }

        if ($processed < $total)
        {
            if ($this->attempts() < 3)
            {
                $this->release(60);
                return;
            }

            $tracker = ImportTracker::query()
                ->where('tracker_id', $this->trackerId)
                ->first();
            if ($tracker)
            {
                $tracker->update(['status' => 'failed']);
                return;
            }
        }

        $startTime = microtime(true);
        $key = "import:$this->trackerId:pages";
        $totalPages = Redis::client()->lrange($key, 0, -1);
        $data = (function() use ($totalPages)
        {
          foreach($totalPages as $page)
          {
            $pageData = json_decode($page, true);
            if (is_array($pageData))
            {
                foreach($pageData as $record)
                {
                    yield $record;
                }
            }
          }
        })();

        $inserted = call_user_func([$this->modelClass, 'RefreshTable'], $data);

        $executionTime = round(microtime(true) - $startTime, 2);
        $mins = floor($executionTime / 60);
        $seconds = round($executionTime - ($mins * 60));
        $formattedTime = sprintf("%d мин. %d сек.", $mins, $seconds);

        Log::info("$this->trackerId : Импорт завершен, сущность: $tracker->entity, загружено: $inserted, времени затрачено: $formattedTime");
        Redis::client()->del($key);
        Redis::client()->del("import:$this->trackerId:processed");
        Redis::client()->del("import:$this->trackerId:total");

        ImportTracker::query()->where('tracker_id', $this->trackerId)
            ->update(['status' => 'completed']);
    }
}
