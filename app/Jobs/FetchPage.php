<?php

namespace App\Jobs;

use App\Models\ImportTracker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class FetchPage implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    protected array $params;
    protected int $page;
    protected string $trackerId;
    protected string $entity;

    public function __construct(array $params, int $page, string $trackerId, string $entity)
    {
        $this->params = $params;
        $this->page = $page;
        $this->trackerId = $trackerId;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $url = config('settings.APIUrl') . $this->entity;

        $query = $this->buildQuery();

        $response = Http::retry(3, 1000,
            function ($e) {
                if ($e->response && $e->response->status() == 429) {
                    return true;
                }

                if ($e instanceof ConnectionException) {
                    return true;
                }
                return false;
            })->get($url, $query);

        if ($response->failed()) {
            Log::error("Ошибка загрузки $this->entity, страница $this->page: " . $response->body());
            $this->fail();
            return;
        }

        $data = $response->json()['data'] ?? [];

        Log::info("$this->trackerId : Страница {$this->page} для {$this->entity}: загружено " . count($data) . " записей");
        if (!empty($data)) {
            Redis::client()->rPush("import:$this->trackerId:pages", json_encode($data));
        }

        Redis::client()->incr("import:$this->trackerId:processed");
        ImportTracker::query()
            ->where('tracker_id', $this->trackerId)
            ->increment('processed_pages');
    }

    protected function BuildQuery() : array
    {
        $query = [
            'key' => $this->params['key'],
            'dateFrom' => $this->params['dateFrom'],
            'page' => $this->page,
        ];

        if ($this->entity !== 'stocks')
        {
            $query['dateTo'] = $this->params['dateTo'];
        }
        return $query;
    }
}
