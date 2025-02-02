<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Title\TitleRequest;
use App\Http\Requests\Title\UpdateTitleRequest;
use App\Http\Requests\Title\UpdateTitleStatusRequest;
use App\Services\Title\TitleService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TitleController extends Controller
{
    private $titleService;
    public function __construct(TitleService $titleService)
    {
        $this->titleService = $titleService;
    }

    public function getAll()
    {
        return $this->titleService->getAllTitles();
    }

    public function getByStudent(Request $request)
    {
        $title = $this->titleService->getTitleByStudent($request);
        return ResponseHelper::success($title, 'Get title successfully');
    }

    public function getBySupervisor(Request $request)
    {
        $title = $this->titleService->getTitleBySupervisor($request);
        return ResponseHelper::success($title, 'Get title successfully');
    }


    public function store(TitleRequest $request)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $title = $this->titleService->createTitle($data, $studentId);
            return ResponseHelper::success($title, 'Title succesfully added', 201);
        } catch (ConflictException $e) {
            return ResponseHelper::error($e->getMessage(), 409);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function update(UpdateTitleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $user = Auth::user();
            $data['student_id'] = $user->student->id;
            if ($user->hasRole('mahasiswa') && isset($data['status']) && !in_array($data['status'], ['submitted', 'draft'])) {
                return ResponseHelper::error('You are not allowed to set this status.', 403);
            }
            $title = $this->titleService->updateTitle($id, $data, $user->student->id);
            return ResponseHelper::success($title, 'Updated title successfully');
        } catch (AuthorizationException $e) {
            return ResponseHelper::error($e->getMessage(), 403);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function destroy($id)
    {
        try {
            $titleId = $this->titleService->delete($id);
            return ResponseHelper::success($titleId, 'Delete title successfully');
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function updateBySupervisor(UpdateTitleStatusRequest $request, $id)
    {
        try {
            $title = $this->titleService->updateStatusBySupervisor($id, $request->validated());
            return ResponseHelper::success($title, 'Update status successfullly');
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }
}
