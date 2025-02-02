<?php

namespace App\Repositories\Grade;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Grade;
use Illuminate\Support\Facades\Log;

class GradeRepositoryImplement extends Eloquent implements GradeRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Grade $model;

    public function __construct(Grade $model)
    {
        $this->model = $model;
    }

    public function createForGradable(array $data)
    {
        return $this->model->create($data);
    }

    public function findByGradableAndUser(string $gradableId, string $lecturerId, string $role)
    {
        return $this->model
            ->where('gradable_id', $gradableId)
            ->where('lecturer_id', $lecturerId)
            ->where('role_in_activity', $role)
            ->first();
    }

    public function updateForGrade(string $id, array $data)
    {
        $grade = $this->findOrFail($id);
        if ($grade) {
            $grade->update($data);
            return $grade;
        }
        return null;
    }
}
