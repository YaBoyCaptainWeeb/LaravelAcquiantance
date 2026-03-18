<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class SalesController extends BaseController
{
    protected function path(): string
    {
        return 'sales/';
    }

    protected function specificRules(): array
    {
       return [
           'dateFrom' => 'required|date_format:Y-m-d',
           'dateTo' => 'required|date_format:Y-m-d',
       ];
    }

    /**
     * @throws ConnectionException
     */
    protected function handle(array $validated, array $data, int $pagesCount)
    {
        if ($pagesCount > 1)
        {
            for ($page = 2; $page < $pagesCount; $page++)
            {
                $response = $this->RequestData($validated,$page)->json();

                $data = array_merge($data, $response['data']) ?? [];
            }
        }
        try {
            if (empty($data))
            {
                return "Данных нет, вносить нечего";
            }
            $result = Sales::RefreshTable($data);
            return "Успешно внесено: $result элементов";
        } catch (Throwable $th) {
            return json_encode([
                'error' => $th->getCode(),
                'message' => $th->getMessage()
            ]);
        }
    }
}
