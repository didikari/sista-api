<?php

namespace App\Services\Seminar;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Repositories\Lecturer\LecturerRepository;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Seminar\SeminarRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Title\TitleRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class SeminarServiceImplement extends ServiceApi implements SeminarService
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
    protected SeminarRepository $mainRepository;
    protected TitleRepository $titleRepository;
    protected LecturerRepository $lecturerRepository;
    protected StudentRepository $studentRepository;


    public function __construct(
        SeminarRepository $mainRepository,
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

    public function createSeminar(array $data, $studentId)
    {
        $seminar = $this->mainRepository->findByStudentId($studentId);
        if ($seminar) {
            throw new ConflictException('A Seminar record already exists.');
        }

        $title = $this->titleRepository->getByStudent($studentId);
        if (!$title || $title->isEmpty()) {
            throw new NotFoundException('Title not found for the student.');
        }

        $data['title_id'] = $title->pluck('id')->first();
        $data['student_id'] = $studentId;
        $data['status'] = 'pending';
        $data['submission_date'] = now();

        if (isset($data['seminar_file'])) {
            $data['seminar_file'] = $this->storeFile($data['seminar_file']);
        }

        try {
            return $this->mainRepository->createSeminar($data);
        } catch (\Exception $e) {
            throw new Exception('Error storing seminar: ' . $e->getMessage());
        }
    }

    public function updateSeminarByKaprodi(array $data, $id, $lecturerId)
    {
        try {
            $seminar = $this->mainRepository->findOrFail($id);
            $this->authorizeKaprodiAccess($lecturerId, $seminar->student_id);
            return $this->mainRepository->updateByKaprodi($id, $data);
        } catch (ModelNotFoundException | AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error update seminar: ' . $e->getMessage());
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
        return $file->store('uploads/seminar', 'public');
    }
}
