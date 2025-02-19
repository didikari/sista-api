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

    public function getAll()
    {
        return $this->model->all();
    }

    public function getByDosen(string $guidanceId)
    {
        return $this->model
            ->where('guidance_id', $guidanceId)
            ->latest('created_at')
            ->first();
    }

    public function getByStudent(string $guidanceId)
    {
        return $this->model
            ->where('guidance_id', $guidanceId)
            ->get();
    }

    public function getByKaprodi($user)
    {
        $studyProgramId = $user->lecturer?->study_program_id;

        $latestRecords = \DB::table('guidance_histories')
            ->select('guidance_id', \DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('guidance_id');

        return $this->model
            ->joinSub($latestRecords, 'latest', function ($join) {
                $join->on('guidance_histories.guidance_id', '=', 'latest.guidance_id')
                    ->on('guidance_histories.created_at', '=', 'latest.latest_created_at');
            })
            ->whereHas('guidance.student', function ($query) use ($studyProgramId) {
                $query->where('study_program_id', $studyProgramId);
            })
            ->get();
    }
}
