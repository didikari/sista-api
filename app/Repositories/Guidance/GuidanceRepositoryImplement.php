<?php

namespace App\Repositories\Guidance;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Guidance;

class GuidanceRepositoryImplement extends Eloquent implements GuidanceRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Guidance $model;

    public function __construct(Guidance $model)
    {
        $this->model = $model;
    }

    public function createGuidance(array $data)
    {
        return $this->model->create($data);
    }

    public function updateGuidance($id, array $data)
    {
        $guidance = $this->model->findOrFail($id);
        $guidance->update($data);
        return $guidance;
    }

    public function updateBySupervisor($id, array $data)
    {
        $guidance = $this->model->findOrFail($id);
        $guidance->update($data);
        return $guidance;
    }

    public function findByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)->first();
    }
}
