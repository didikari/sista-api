<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seminar\SeminarRequest;
use App\Http\Requests\Seminar\UpdateSeminarRequest;
use App\Http\Resources\Seminar\SeminarResource;
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

            $seminar = $this->seminarService->getSeminarByRole($role, $user);
            $relations = ['title', 'supervisor', 'examiner'];
            if ($role !== 'mahasiswa') {
                $relations[] = 'student';
            }
            $seminar->load($relations);

            if ($seminar === null) {
                return ResponseHelper::error('Unauthorized role', 403);
            }

            return ResponseHelper::success(SeminarResource::collection($seminar), 'Get data exam successfully', 200);
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


    public function show($id)
    {
        try {
            $role = Auth::user()->getRoleNames()->first();
            $seminar = $this->seminarService->findById($id);
            $relations = ['title', 'supervisor', 'examiner'];
            if ($role !== 'mahasiswa') {
                $relations[] = 'student';
            }
            $seminar->load($relations);
            return ResponseHelper::success(new SeminarResource($seminar), 'Get Seminar succesfully', 200);
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
            $seminar = $this->seminarService->updateSeminarByRole($role, $id, $user, $data);
            return ResponseHelper::success($seminar, 'Updated Seminar successfuly', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
