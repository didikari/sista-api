<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\NotFoundException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Grade\GradeRequest;
use App\Http\Requests\Grade\UpdateGradeRequest;
use App\Models\Exam;
use App\Models\PreSeminar;
use App\Models\Seminar;
use App\Services\Grade\GradeService;
use App\Traits\ModelNameNormalizer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    use ModelNameNormalizer;
    private $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function storeGrade(GradeRequest $request, $gradable, $id, $role)
    {
        try {
            $modelClass = $this->normalizeModelName($gradable);

            if (!class_exists($modelClass)) {
                return ResponseHelper::error("Invalid gradable model", 400);
            }

            $gradableModel = $modelClass::findOrFail($id);
            $data = $request->validated();
            $lecturerId = Auth::user()->lecturer->id;

            $data['gradable_id'] = $gradableModel->id;
            $data['gradable_type'] = get_class($gradableModel);
            $data['student_id'] = $lecturerId;
            $data['role_in_activity'] = $role;

            $grade = $this->gradeService->createGrade($data, $lecturerId, $role);

            return ResponseHelper::success($grade, "Create grade {$gradableModel->getMorphClass()} {$role} successfully", 201);
        } catch (ModelNotFoundException | NotFoundException $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }

    public function updateForGrade(UpdateGradeRequest $request, $gradable, $id, $role)
    {

        try {
            $modelClass = $this->normalizeModelName($gradable);

            if (!class_exists($modelClass)) {
                return ResponseHelper::error("Invalid gradable model", 400);
            }

            $gradableModel = $modelClass::findOrFail($id);
            $data = $request->validated();
            $lecturerId = Auth::user()->lecturer->id;

            $data['gradable_id'] = $gradableModel->id;
            $data['gradable_type'] = get_class($gradableModel);
            $data['user_id'] = $lecturerId;
            $data['role_in_activity'] = $role;

            $grade = $this->gradeService->updateGrade($data, $lecturerId, $role, $id);

            return ResponseHelper::success($grade, "Update grade {$gradableModel->getMorphClass()} {$role} successfully", 200);
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error($e->getMessage(), 404);
        } catch (\Exception $e) {
            return ResponseHelper::exception($e);
        }
    }
}
