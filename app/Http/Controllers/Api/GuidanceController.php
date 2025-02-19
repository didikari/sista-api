<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Guidance\GuidanceRequest;
use App\Http\Requests\Guidance\UpdateGuidanceRequest;
use App\Http\Requests\GuidanceHistory\GuidanceHistoryRequest;
use App\Http\Resources\Guidance\GuidanceResource;
use App\Services\Guidance\GuidanceService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GuidanceController extends Controller
{
    private $guidanceService;
    public function __construct(GuidanceService $guidanceService)
    {
        $this->guidanceService = $guidanceService;
    }

    public function index()
    {
        try {
            $studentId = Auth::user()->student->id;
            $guidance = $this->guidanceService->getGuidance($studentId);
            $guidance->load(['student', 'student.title', 'supervisor']);
            return ResponseHelper::success(new GuidanceResource($guidance), 'Get guidance successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function store(GuidanceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['student_id'] = Auth::user()->student->id;
            $guidance = $this->guidanceService->createGuidance($data);
            return ResponseHelper::success($guidance, 'Created guidance successfully', 201);
        } catch (ConflictException $e) {
            return ResponseHelper::error($e->getMessage(), 409);
        } catch (\Exception $e) {
            ResponseHelper::exception($e);
        }
    }


    public function show($id)
    {
        try {
            $guidance = $this->guidanceService->findById($id);
            return ResponseHelper::success(new GuidanceResource($guidance), 'Get guidance succesfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function update($id, UpdateGuidanceRequest $request)
    {
        $data = $request->validated();
        $studentId = Auth::user()->student->id;
        $guidance = $this->guidanceService->updateGuidance($id, $data, $studentId);
        return ResponseHelper::success($guidance, 'Updated guidance successfully', 200);
    }

    public function updateBySupervisor(GuidanceHistoryRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $supervisorId = Auth::user()->lecturer->id;
            $guidance =  $this->guidanceService->updateBySupervisor($id, $data, $supervisorId);
            return ResponseHelper::success($guidance, 'Updated guidance successfully', 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }
}
