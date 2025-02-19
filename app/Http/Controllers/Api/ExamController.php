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

    public function show($id)
    {
        try {
            $role = Auth::user()->getRoleNames()->first();
            $exam = $this->examService->findById($id);
            $relations = ['title', 'supervisor', 'examiner'];
            if ($role !== 'mahasiswa') {
                $relations[] = 'student';
            }
            $exam->load($relations);
            return ResponseHelper::success(new ExamResource($exam), 'Get Exam succesfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();
            $data = $request->all();
            $exam = $this->examService->updateExamByRole($role, $id, $user, $data);
            return ResponseHelper::success($exam, 'Updated Exam successfuly', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
