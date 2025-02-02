<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seminar\SeminarRequest;
use App\Http\Requests\Seminar\UpdateSeminarRequest;
use App\Services\Seminar\SeminarService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeminarController extends Controller
{

    private $seminarService;
    public function __construct(SeminarService $seminarService)
    {
        $this->seminarService = $seminarService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            $seminar = $this->seminarService->getExamsByRole($role, $user);
            $seminar->load(['title', 'student', 'supervisor', 'examiner']);

            if ($seminar === null) {
                return ResponseHelper::error('Unauthorized role', 403);
            }

            return ResponseHelper::success($seminar, 'Get data exam successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SeminarRequest $request)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $seminar = $this->seminarService->createSeminar($data, $studentId);
            return ResponseHelper::success($seminar, 'Create Seminar successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }


    public function updateByKaprodi(UpdateSeminarRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $lecturerId = Auth::user()->lecturer->id;
            $seminar = $this->seminarService->updateSeminarByKaprodi($data, $id, $lecturerId);
            return ResponseHelper::success($seminar, 'Update Seminar successfuly', 200);
        } catch (AuthorizationException $e) {
            return ResponseHelper::error($e->getMessage(), 403);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
