<?php

namespace App\Services\Guidance;

use App\Enums\GuidanceStatus;
use App\Exceptions\ConflictException;
use App\Models\GuidanceHistory;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Guidance\GuidanceRepository;
use App\Repositories\GuidanceHistory\GuidanceHistoryRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GuidanceServiceImplement extends ServiceApi implements GuidanceService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title = "";
    /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected GuidanceRepository $mainRepository;
    protected GuidanceHistoryRepository $guidanceHistory;

    public function __construct(GuidanceRepository $mainRepository, GuidanceHistoryRepository $guidanceHistory)
    {
        $this->mainRepository = $mainRepository;
        $this->guidanceHistory = $guidanceHistory;
    }

    public function createGuidance(array $data)
    {
        try {
            $data['status'] = 'pending';
            $data['guidance_date'] = now();
            $existingGuidance = $this->mainRepository->findByStudentId($data['student_id']);

            if ($existingGuidance) {
                throw new ConflictException('A guidance record already exists.');
            }

            if (isset($data['proposal_file'])) {
                $filePath = $data['proposal_file']->store('uploads/guidance', 'public');
                $data['proposal_file'] = $filePath;
            }

            return  $this->mainRepository->createGuidance($data);
        } catch (ConflictException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error create data guidance: ' . $e->getMessage());
        }
    }

    public function updateGuidance($id, array $data, $studentId)
    {
        try {
            $guidance = $this->mainRepository->findOrFail($id);
            if ($guidance->student_id !== $studentId) {
                throw new AuthorizationException('You are not authorized to update this guidance.');
            }
            if (isset($data['proposal_file'])) {
                if ($guidance->proposal_file) {
                    Storage::disk('public')->delete($guidance->proposal_file);
                }
                $filePath = $data['proposal_file']->store('uploads/guidance', 'public');
                $data['proposal_file'] = $filePath;
            }

            return $this->mainRepository->updateGuidance($id, $data);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error updating guidance : ' . $e->getMessage());
        }
    }

    public function updateBySuperVisor($id, array $data, $supervisorId)
    {
        try {
            $guidance = $this->mainRepository->findOrFail($id);
            $status = ['status' => $data['status']];
            if ($guidance->supervisor_id !== $supervisorId) {
                throw new AuthorizationException('You do not have access to update this status as you are not the assigned supervisor.');
            }

            $updateGuidance = $this->mainRepository->updateBySuperVisor($id, $status);
            if ($updateGuidance) {
                $status = $this->determineStatus($updateGuidance['status']->value);
                $historyData = [
                    'guidance_id'   => $id,
                    'guidance_date' => now(),
                    'notes'         => $data['notes'] ?? null,
                    'feedback'      => $data['feedback'] ?? null,
                    'status'        => $status,
                ];
                $history = $this->guidanceHistory->createHistory($historyData);
            };

            return [
                'guidance' => $updateGuidance,
                'history' => $history ?? []
            ];
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error updating guidance : ' . $e->getMessage());
        }
    }

    private function determineStatus($status)
    {
        return ($status === 'approved') ? 'completed' : 'pending';
    }
}
