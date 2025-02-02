<?php

namespace App\Services\PreSeminar;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Repositories\Lecturer\LecturerRepository;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\PreSeminar\PreSeminarRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Title\TitleRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PharIo\Manifest\AuthorCollection;

class PreSeminarServiceImplement extends ServiceApi implements PreSeminarService
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
    protected PreSeminarRepository $mainRepository;
    protected TitleRepository $titleRepository;
    protected LecturerRepository $lecturerRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        PreSeminarRepository $mainRepository,
        TitleRepository $titleRepository,
        LecturerRepository $lecturerRepository,
        StudentRepository $studentRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->titleRepository = $titleRepository;
        $this->lecturerRepository = $lecturerRepository;
        $this->studentRepository = $studentRepository;
    }

    public function getExamsByRole(string $role, $user)
    {

        switch ($role) {
            case 'kaprodi':
                return $this->mainRepository->getByKaprodi($user);
            case 'dosen':
                return $this->mainRepository->getByDosen($user->lecturer->id);
            case 'admin':
                return $this->mainRepository->getAll();
            case 'mahasiswa':
                return $this->mainRepository->getByStudent($user->student->id);
            default:
                return null;
        }
    }

    public function createPreSeminar(array $data, $studentId)
    {
        $existingPreseminar = $this->mainRepository->findByStudentId($studentId);
        if ($existingPreseminar) {
            throw new ConflictException('A Pre-seminar record already exists.');
        }

        $title = $this->titleRepository->getByStudent($studentId);
        if (!$title || $title->isEmpty()) {
            throw new NotFoundException('Title not found for the student.');
        }

        $data['title_id'] = $title->pluck('id')->first();
        $data['student_id'] = $studentId;
        $data['status'] = 'pending';
        $data['submission_date'] = now();

        if (isset($data['pre_seminar_file'])) {
            $data['pre_seminar_file'] = $this->storeFile($data['pre_seminar_file']);
        }

        try {
            return $this->mainRepository->createPreSeminar($data);
        } catch (\Exception $e) {
            throw new Exception('Error storing pre-seminar: ' . $e->getMessage());
        }
    }

    public function updatePreSeminarByKaprodi(array $data, $id, $lecturerId)
    {
        try {
            $preSeminar = $this->mainRepository->findOrFail($id);
            $this->authorizeKaprodiAccess($lecturerId, $preSeminar->student_id);
            return $this->mainRepository->updateByKaprodi($id, $data);
        } catch (ModelNotFoundException | AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error update pre-seminar: ' . $e->getMessage());
        }
    }

    private function authorizeKaprodiAccess($lecturerId, $studentId)
    {
        $kaprodiStudy = $this->lecturerRepository->getStudyProgramByUserId($lecturerId);
        $studentStudy = $this->studentRepository->getStudyProgramByUserId($studentId);

        if ($kaprodiStudy->id !== $studentStudy->id) {
            throw new AuthorizationException('You do not have access to update this status as you are not the assigned kaprodi.');
        }
    }

    private function storeFile($file)
    {
        return $file->store('uploads/pre-seminar', 'public');
    }
}
