<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\ExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Resources\Exam\ExamResource;
use App\Services\Exam\ExamService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{

    private $examService;
    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            $exams = $this->examService->getExamsByRole($role, $user);
            $exams->load(['title', 'student', 'supervisor', 'examiner']);

            if ($exams === null) {
                return ResponseHelper::error('Unauthorized role', 403);
            }

            return ResponseHelper::success(ExamResource::collection($exams), 'Get data exam successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExamRequest $request)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $exam = $this->examService->createExam($data, $studentId);
            return ResponseHelper::success($exam, 'Create exam successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }


    public function updateByKaprodi(UpdateExamRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $lecturerId = Auth::user()->lecturer->id;
            $exam = $this->examService->updateExamByKaprodi($data, $id, $lecturerId);
            return ResponseHelper::success($exam, 'Update Exam successfuly', 200);
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
