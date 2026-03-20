<?php

namespace App\Http\Controllers;

use App\Jobs\FetchPage;
use App\Jobs\FinalizeImport;
use App\Models\ImportTracker;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

abstract class BaseController extends Controller
{
    // Properties
    protected array $query;
    protected function now() : string {
        return date('Y-m-d');
    }

    abstract protected function path() : string;
    abstract protected function getEntity() : string;
    abstract protected function getModelClass() : string;
    protected array $baseRules = [
        'key' => 'required|string'
    ];


    abstract protected function specificRules() : array;

    public function rules() : array
    {
        return array_merge($this->baseRules, $this->specificRules());
    }

    // Functions

    /**
     * @throws ConnectionException
     */
    protected function RequestData(array $validated, $page = 1)
    {
        $this->BuildQuery($validated, $page);

        $response = Http::get(config('settings.APIUrl') . $this->path(), $this->query);
        if ($response->failed())
        {
            abort(response()->json(
                [
                    'error' => $response->status(),
                    'message' => $response->json() ?? $response->body()
                ],
                $response->status()
            ));
        }
        return $response;
    }

    protected function BuildQuery(array $validated, $page) : void
    {
        $query = [
            'key' => $validated['key'],
            'dateFrom' => $this instanceof StocksController ? $this->now() : $validated['dateFrom'],
            'page' => $page
        ];

        if (isset($validated['dateTo'])) {
            $query['dateTo'] = $validated['dateTo'];
        }

        $this->query = $query;
    }
    /**
     * @throws ConnectionException
     */
    public function __invoke(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        $validated = $request->validate($this->rules());

        $response = $this->RequestData($validated)->json();

        $data = $response['data'] ?? [];
        $pagesCount = $response['meta']['last_page'] ?? 1;
//        return $this->handle($validated, $data, $pagesCount);
        return $this->CreateQueue($data, $pagesCount);
    }

    protected function CreateQueue(array $data, int $pagesCount) {
        $trackerId = (string) Str::uuid();
        $entity = $this->getEntity();
        $modelClass = $this->getModelClass();

        $redis = Redis::client();

        if (!empty($data))
        {
            $redis->rPush("import:$trackerId:entity", json_encode($data));
        }

        $redis->setex("import:$trackerId:total", 3600, $pagesCount);
        $redis->setex("import:$trackerId:processed", 3600, 1);

        ImportTracker::query()->create([
            'tracker_id' => $trackerId,
            'entity' => $entity,
            'total_pages' => $pagesCount,
            'processed_pages' => 1,
            'status' => 'processing'
        ]);

        $interval = 1;

        for ($page = 2; $page <= $pagesCount; $page++)
        {
            $delay = ($page - 2) * $interval;
            FetchPage::dispatch($this->query, $page, $trackerId, $entity)
                ->delay(now()->addSeconds($delay));
        }

        $finalDelay = ($pagesCount - 1) * $interval + 10;
        FinalizeImport::dispatch($trackerId, $modelClass)
            ->delay(now()->addSeconds($finalDelay));

        return response()->json([
            'message' => 'Импорт запущен',
            'entity' => $entity,
            'tracker_id' => $trackerId,
            'total_pages' => $pagesCount,
        ], 202);
    }
}
