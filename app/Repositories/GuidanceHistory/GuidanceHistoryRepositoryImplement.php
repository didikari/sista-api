<?php

namespace App\Repositories\GuidanceHistory;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\GuidanceHistory;

class GuidanceHistoryRepositoryImplement extends Eloquent implements GuidanceHistoryRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected GuidanceHistory $model;

    public function __construct(GuidanceHistory $model)
    {
        $this->model = $model;
    }

    public function createHistory(array $data)
    {
        return $this->model->create($data);
    }
}
