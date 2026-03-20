<?php

namespace App\Http\Controllers;

use App\Models\Stocks;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class StocksController extends BaseController
{
    protected function specificRules() : array
    {
        return [];
    }

    protected function path() : string
    {
        return 'stocks/';
    }

    protected function getEntity(): string
    {
        return 'stocks';
    }

    protected function getModelClass(): string
    {
        return Stocks::class;
    }

    /**
     * @throws ConnectionException
     * @throws Throwable
     */
//    protected function handle(array $validated, array $data, int $pagesCount)
//    {
//        if ($pagesCount > 1)
//        {
//            for ($page = 2; $page < $pagesCount; $page++)
//            {
//                $response = $this->RequestData($validated,$page)->json();
//
//                $data = array_merge($data, $response['data']) ?? [];
//            }
//        }
//        try {
//            if (empty($data))
//            {
//                return "Данных нет, вносить нечего";
//            }
//            $result = Stocks::RefreshTable($data);
//            return "Успешно внесено: $result элементов";
//        } catch (Throwable $th) {
//            return json_encode([
//                'error' => $th->getCode(),
//                'message' => $th->getMessage()
//            ]);
//        }
//
//    }
}
