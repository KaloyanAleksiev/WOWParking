<?php

namespace App\Repositories\Rates;


use App\Models\Rate;
use App\Repositories\Interfaces\Rates\RateRepositoryInterface;

class RateRepository implements RateRepositoryInterface
{
    protected $model;

    public function __construct(Rate $rate)
    {
        $this->model = $rate;
    }

    public function getAllRates($fields = ['*'])
    {
        return $this->model->select($fields)->get();
    }
}