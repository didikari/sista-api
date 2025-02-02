<?php

namespace App\Repositories\Lecturer;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Lecturer;

class LecturerRepositoryImplement extends Eloquent implements LecturerRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Lecturer $model;

    public function __construct(Lecturer $model)
    {
        $this->model = $model;
    }

    public function getStudyProgramByLecturerId($lecturerId)
    {
        $lecturer = $this->model->where('id', $lecturerId)->first();
        return $lecturer->studyProgram;
    }
}
