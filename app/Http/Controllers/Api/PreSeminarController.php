<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreSeminar\PreSeminarRequest;
use App\Http\Requests\PreSeminar\UpdatePreSeminarRequest;
use App\Http\Resources\PreSeminar\PreSeminarResource;
use App\Services\PreSeminar\PreSeminarService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreSeminarController extends Controller
{

    private $preSeminarService;
    public function __construct(PreSeminarService $preSeminarService)
    {
        $this->preSeminarService = $preSeminarService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames()->first();

            $preSeminar = $this->preSeminarService->getPreSeminarByRole($role, $user);
            if ($preSeminar === null) {
                return ResponseHelper::error('Unauthorized role', 403);
            }
            $relations = ['title', 'supervisor', 'examiner'];
            if ($role !== 'mahasiswa') {
                $relations[] = 'student';
            }

            $preSeminar->load($relations);

            return ResponseHelper::success(PreSeminarResource::collection($preSeminar), 'Get data exam successfully', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PreSeminarRequest $request)
    {
        try {
            $data = $request->validated();
            $studentId = Auth::user()->student->id;
            $preSeminar = $this->preSeminarService->createPreSeminar($data, $studentId);
            return ResponseHelper::success($preSeminar, 'Create preseminar successfully', 201);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }


    public function show($id)
    {
        try {
            $role = Auth::user()->getRoleNames()->first();
            $preSeminar = $this->preSeminarService->findById($id);
            $relations = ['title', 'supervisor', 'examiner'];
            if ($role !== 'mahasiswa') {
                $relations[] = 'student';
            }
            $preSeminar->load($relations);
            return ResponseHelper::success(new PreSeminarResource($preSeminar), 'Get PreSeminar succesfully', 200);
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
            $preSeminar = $this->preSeminarService->updatePreSeminarByRole($role, $id, $user, $data);
            return ResponseHelper::success($preSeminar, 'Updated PreSeminar successfuly', 200);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }


    public function destroy(string $id)
    {
        //
    }
}
