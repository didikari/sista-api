<?php

namespace App\Repositories\Title;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Title;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TitleRepositoryImplement extends Eloquent implements TitleRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Title $model;

    public function __construct(Title $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByStudent($studentId)
    {
        return $this->model->where('student_id', $studentId)->get();
    }

    public function getBySupervisor($lecturerId)
    {
        return $this->model->where('supervisor_id', $lecturerId)->get();
    }

    public function createTitle(array $data)
    {
        return $this->model->create($data);
    }

    public function deleteTitle($titleId)
    {
        $title = $this->model->findOrFail($titleId);
        if ($title->proposal_file) {
            Storage::disk('public')->delete($title->proposal_file);
        }
        $title->delete();
        return $title;
    }

    public function updateTitle($titleId, array $data)
    {
        $title = $this->model->findOrFail($titleId);
        $title->update($data);
        return $title;
    }

    public function updateBySupervisor($id, array $data)
    {
        $title = $this->model->findOrFail($id);
        $title->update($data);
        return $title;
    }

    public function findByStudentId($studentId)
    {
        return $this->model->where('student_id', $studentId)->first();
    }

    public function findById(string $id)
    {
        return $this->model->with('supervisor.user')->findOrFail($id);
    }
}
