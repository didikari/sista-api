<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ConflictException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PreSeminar\PreSeminarRequest;
use App\Http\Requests\PreSeminar\UpdatePreSeminarRequest;
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

            $preSeminar = $this->preSeminarService->getExamsByRole($role, $user);
            $preSeminar->load(['title', 'student', 'supervisor', 'examiner']);

            if ($preSeminar === null) {
                return ResponseHelper::error('Unauthorized role', 403);
            }

            return ResponseHelper::success($preSeminar, 'Get data exam successfully', 200);
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


    public function updateByKaprodi(UpdatePreSeminarRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $lecturerId = Auth::user()->lecturer->id;
            $preSeminar = $this->preSeminarService->updatePreSeminarByKaprodi($data, $id, $lecturerId);
            return ResponseHelper::success($preSeminar, 'Update PreSeminar successfuly', 200);
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
