<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class OrdersController extends BaseController
{
    protected function path(): string
    {
        return 'orders/';
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
        return 'orders';
    }

    protected function getModelClass(): string
    {
        return Orders::class;
    }
}
