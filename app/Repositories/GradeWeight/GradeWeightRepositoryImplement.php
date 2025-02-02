<?php

namespace App\Repositories\GradeWeight;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\GradeWeight;

class GradeWeightRepositoryImplement extends Eloquent implements GradeWeightRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected GradeWeight $model;

    public function __construct(GradeWeight $model)
    {
        $this->model = $model;
    }

    public function getWeightByRole($roleName)
    {
        return $this->model->where('role', $roleName)->first();
    }
}
