<?php

namespace App\Services\Title;

use App\Enums\TitleStatus;
use App\Exceptions\ConflictException;
use App\Http\Resources\Title\TitleResource;
use App\Http\Resources\Title\TitleSupervisorResource;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Title\TitleRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TitleServiceImplement extends ServiceApi implements TitleService
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
    protected TitleRepository $mainRepository;

    public function __construct(TitleRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllTitles()
    {
        return $this->mainRepository->getAll();
    }

    public function getTitleByStudent(Request $request)
    {
        try {
            $studentId = $request->user()->student->id;
            $title = $this->mainRepository->getByStudent($studentId);
            return TitleResource::collection($title);
        } catch (\Exception $e) {
            throw new Exception('Get title failed', 500);
        }
    }

    public function getTitleBySupervisor(Request $request)
    {
        try {
            $lecturerId = $request->user()->lecturer->id;
            $title = $this->mainRepository->getBySupervisor($lecturerId);
            return TitleSupervisorResource::collection($title);
        } catch (\Exception $e) {
            throw new Exception('Get title failed', 500);
        }
    }

    public function createTitle(array $data, $studentId)
    {
        try {
            $data['student_id'] = $studentId;
            $existingTitle = $this->mainRepository->findByStudentId($studentId);

            if ($existingTitle) {
                throw new ConflictException('A guidance record already exists.');
            }

            if (isset($data['proposal_file'])) {
                $filePath = $data['proposal_file']->store('uploads/titles', 'public');
                $data['proposal_file'] = $filePath;
            }
            return  $this->mainRepository->createTitle($data);
        } catch (ConflictException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error creating title: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $title = $this->mainRepository->deleteTitle($id);
            return $title;
        } catch (\Exception $e) {
            throw new Exception('Error deleting title: ' . $e->getMessage());
        }
    }


    public function updateTitle($titleId, array $data, $studentId)
    {
        try {
            $title = $this->mainRepository->findOrFail($titleId);
            if ($title->student_id !== $studentId) {
                throw new AuthorizationException('You are not authorized to update this title.');
            }
            if (isset($data['proposal_file'])) {
                if ($title->proposal_file) {
                    Storage::disk('public')->delete($title->proposal_file);
                }
                $filePath = $data['proposal_file']->store('uploads/titles', 'public');
                $data['proposal_file'] = $filePath;
            }
            return $this->mainRepository->updateTitle($titleId, $data);
        } catch (AuthorizationException $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Title not found with ID: ' . $titleId, 404);
        } catch (\Exception $e) {
            throw new Exception('Error updating title: ' . $e->getMessage());
        }
    }

    public function updateStatusBySupervisor($id, array $data)
    {
        try {
            $title = $this->mainRepository->findOrFail($id);
            $user = Auth::user()->lecturer->id;
            if ($title->supervisor_id !== $user) {
                throw new AuthorizationException('You are not authorized to update this title.');
            }
            $updateTitle = $this->mainRepository->updateBySupervisor($id, $data);
            return new TitleSupervisorResource($updateTitle);
        } catch (\Exception $e) {
            throw new Exception('Error updating title: ' . $e->getMessage());
        }
    }
}
