<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Client\ConnectionException;

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

    protected function getEntity(): string
    {
        return 'sales';
    }

    protected function getModelClass(): string
    {
        return Sales::class;
    }
}
