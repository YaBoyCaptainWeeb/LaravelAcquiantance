<?php

namespace App\Http\Controllers;

use App\Models\Incomes;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class IncomesController extends BaseController
{

    protected function path(): string
    {
        return 'incomes/';
    }

    protected function specificRules(): array
    {
        return [
            'dateFrom' => 'required|date_format:Y-m-d',
            'dateTo' => 'required|date_format:Y-m-d',
        ];
    }
    protected function getEntity(): string
    {
        return 'incomes';
    }

    protected function getModelClass(): string
    {
        return Incomes::class;
    }
}
