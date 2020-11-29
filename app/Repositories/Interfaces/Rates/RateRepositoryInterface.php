<?php

namespace App\Repositories\Interfaces\Rates;


use App\Models\Rate;

interface RateRepositoryInterface
{
    public function __construct(Rate $rate);

    public function getAllRates($fields = ['*']);
}