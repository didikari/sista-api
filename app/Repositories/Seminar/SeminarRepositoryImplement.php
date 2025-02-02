<?php

namespace App\Repositories\Seminar;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Seminar;

class SeminarRepositoryImplement extends Eloquent implements SeminarRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Seminar $model;

    public function __construct(Seminar $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByDosen(string $dosenId)
    {
        return $this->model
            ->where('supervisor_id', $dosenId)
            ->orWhere('examiner_id', $dosenId)
            ->get();
    }

    public function getByStudent(string $studentId)
    {
        return $this->model
            ->where('student_id', $studentId)
            ->get();
    }

    public function getByKaprodi($user)
    {
        $studyProgramId = $user->lecturer?->study_program_id;
        return $this->model->whereHas('student', function ($query) use ($studyProgramId) {
            $query->where('study_program_id', $studyProgramId);
        })->get();
    }

    public function createSeminar(array $data)
    {
        return $this->model->create($data);
    }

    public function findByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)->first();
    }

    public function updateByKaprodi($id, array $data)
    {
        $preSeminar = $this->model->findOrFail($id);
        $preSeminar->update($data);
        return $preSeminar;
    }
}
