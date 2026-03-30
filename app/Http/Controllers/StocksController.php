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
}
