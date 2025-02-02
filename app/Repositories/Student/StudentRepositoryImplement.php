<?php

namespace App\Repositories\Student;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Student;

class StudentRepositoryImplement extends Eloquent implements StudentRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Student $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }


    public function getStudyProgramByStudentId($studentId)
    {
        $student = $this->model->where('id', $studentId)->first();
        return $student->studyProgram;
    }
}
