<?php

namespace App\Services\Grade;

use App\Exceptions\ConflictException;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Grade\GradeRepository;
use App\Repositories\GradeWeight\GradeWeightRepository;
use App\Repositories\PreSeminar\PreSeminarRepository;
use App\Repositories\Seminar\SeminarRepository;
use LaravelEasyRepository\ServiceApi;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class GradeServiceImplement extends ServiceApi implements GradeService
{
    protected string $title = "";
    protected GradeRepository $mainRepository;
    protected PreSeminarRepository $preSeminarRepository;
    protected SeminarRepository $seminarRepository;
    protected ExamRepository $examRepository;
    protected GradeWeightRepository $gradeWeightRepository;

    public function __construct(
        GradeRepository $mainRepository,
        PreSeminarRepository $preSeminarRepository,
        SeminarRepository $seminarRepository,
        ExamRepository $examRepository,
        GradeWeightRepository $gradeWeightRepository,
    ) {
        $this->mainRepository = $mainRepository;
        $this->preSeminarRepository = $preSeminarRepository;
        $this->seminarRepository = $seminarRepository;
        $this->examRepository = $examRepository;
        $this->gradeWeightRepository = $gradeWeightRepository;
    }

    public function createGrade(array $data, string $lecturerId, string $role)
    {
        try {
            $this->validateUserRole($data['gradable_type'], $data['gradable_id'], $lecturerId, $role);
            $this->ensureNoExistingGrade($data['gradable_id'], $lecturerId, $role);

            $weights = $this->gradeWeightRepository->getWeightByRole($role);
            $this->applyWeights($data, $weights);
            $data['lecturer_id'] = $lecturerId;

            return $this->mainRepository->createForGradable($data);
        } catch (AuthorizationException | ConflictException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception('Store grade failed', $e->getMessage());
        }
    }

    public function updateGrade(array $data, string $lecturerId, string $role, string $gradableId)
    {
        try {
            $existingGrade = $this->mainRepository->findByGradableAndUser($gradableId, $lecturerId, $role);

            if (!$existingGrade) {
                throw new NotFoundException("Grade with ID {$gradableId} not found");
            }

            if ($existingGrade->lecturer_id !== $lecturerId || $existingGrade->role_in_activity !== $role) {
                throw new AuthorizationException("User cannot update this grade");
            }

            $weights = $this->gradeWeightRepository->getWeightByRole($role);
            $this->applyWeights($data, $weights);

            $updatedGrade = $this->mainRepository->updateForGrade($existingGrade->id, $data);

            return $updatedGrade;
        } catch (AuthorizationException | NotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception("Update grade failed: {$e->getMessage()}");
        }
    }


    private function validateUserRole(string $gradableType, string $gradableId, string $lecturerId, string $role): void
    {
        $repository = match ($gradableType) {
            'App\Models\PreSeminar' => $this->preSeminarRepository,
            'App\Models\Seminar' => $this->seminarRepository,
            'App\Models\Exam' => $this->examRepository,
            default => throw new AuthorizationException("Invalid gradable type"),
        };

        $gradable = $repository->findOrFail($gradableId);

        if (($role === 'supervisor' && $gradable->supervisor_id !== $lecturerId) ||
            ($role === 'examiner' && $gradable->examiner_id !== $lecturerId)
        ) {
            throw new AuthorizationException("Access denied: {$role} does not match");
        }
    }

    private function ensureNoExistingGrade(string $gradableId, string $lecturerId, string $role): void
    {
        $existingGrade = $this->mainRepository->findByGradableAndUser($gradableId, $lecturerId, $role);
        if ($existingGrade) {
            throw new ConflictException("User has already graded this gradable as a {$role}");
        }
    }

    private function applyWeights(array &$data, $weights): void
    {
        foreach (['a1', 'a2', 'a3', 'a4', 'a5', 'a6'] as $key) {
            $weightKey = "{$key}_weight";
            $data[$key] = number_format($data[$key] * (float) $weights->$weightKey, 2, '.', '');
        }
    }
}
