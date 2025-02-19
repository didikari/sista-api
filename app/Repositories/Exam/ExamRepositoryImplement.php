<?php

namespace App\Repositories\Exam;

use App\Exceptions\NotFoundException;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Exam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ExamRepositoryImplement extends Eloquent implements ExamRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Exam $model;

    public function __construct(Exam $model)
    {
        $this->model = $model;
    }

    public function getByDosen(string $dosenId)
    {
        return $this->model
            ->where('supervisor_id', $dosenId)
            ->orWhere('examiner_id', $dosenId)
            ->get();
    }

    public function getAll()
    {
        return $this->model->all();
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

    public function createExam(array $data)
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

    public function updateById($id, array $data)
    {
        $seminar = $this->model->findOrFail($id);
        $seminar->update($data);
        return $seminar;
    }

    public function findById(string $id)
    {
        return $this->model->findOrFail($id);
    }
}
