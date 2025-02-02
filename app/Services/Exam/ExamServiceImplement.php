<?php

namespace App\Services\Exam;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\Lecturer\LecturerRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Title\TitleRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExamServiceImplement extends ServiceApi implements ExamService
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
    protected ExamRepository $mainRepository;
    protected TitleRepository $titleRepository;
    protected LecturerRepository $lecturerRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        ExamRepository $mainRepository,
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

    public function createExam(array $data, $studentId)
    {
        $exam = $this->mainRepository->findByStudentId($studentId);
        if ($exam) {
            throw new ConflictException('A Exam record already exists.');
        }

        $title = $this->titleRepository->getByStudent($studentId);

        if (!$title || $title->isEmpty()) {
            throw new NotFoundException('Title not found for the student.');
        }

        $data['title_id'] = $title->pluck('id')->first();
        $data['student_id'] = $studentId;
        $data['status'] = 'pending';
        $data['submission_date'] = now();

        if (isset($data['exam_file'])) {
            $data['exam_file'] = $this->storeFile($data['exam_file']);
        }

        try {
            return $this->mainRepository->createExam($data);
        } catch (\Exception $e) {
            throw new Exception('Error storing exam: ' . $e->getMessage());
        }
    }

    public function updateExamByKaprodi(array $data, $id, $lecturerId)
    {
        try {
            $exam = $this->mainRepository->findOrFail($id);
            $this->authorizeKaprodiAccess($lecturerId, $exam->student_id);
            return $this->mainRepository->updateByKaprodi($id, $data);
        } catch (ModelNotFoundException | AuthorizationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new Exception('Error update exam: ' . $e->getMessage());
        }
    }

    private function authorizeKaprodiAccess($lecturerId, $studentId)
    {
        $kaprodiStudy = $this->lecturerRepository->getStudyProgramByLecturerId($lecturerId);
        $studentStudy = $this->studentRepository->getStudyProgramByStudentId($studentId);
        if ($kaprodiStudy->id !== $studentStudy->id) {
            throw new AuthorizationException('You do not have access to update this status as you are not the assigned kaprodi.');
        }
    }

    private function storeFile($file)
    {
        return $file->store('uploads/exam', 'public');
    }
}
