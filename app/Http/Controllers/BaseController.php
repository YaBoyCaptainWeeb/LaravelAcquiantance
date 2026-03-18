<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

abstract class BaseController extends Controller
{
    // Properties
    protected string $ApiURL = 'http://109.73.206.144:6969/api/';

    protected function now() : string {
        return date('Y-m-d');
    }

    abstract protected function path() : string;
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
        $query = [
            'key' => $validated['key'],
            'dateFrom' => $this instanceof StocksController ? $this->now() : $validated['dateFrom'],
            'page' => $page
        ];
        if (isset($validated['dateTo'])) {
            $query['dateTo'] = $validated['dateTo'];
        }

        $response = Http::get($this->ApiURL . $this->path(), $query);
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
    /**
     * @throws ConnectionException
     */
    public function __invoke(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        $validated = $request->validate($this->rules());

        $response = $this->RequestData($validated)->json();

        $data = $response['data'];
        $pagesCount = $response['meta']['last_page'];
        return $this->handle($validated, $data, $pagesCount);
    }

    abstract protected function handle(array $validated, array $data, int $pagesCount);
}
