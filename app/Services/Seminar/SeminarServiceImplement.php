<?php

namespace App\Services\Seminar;

use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
use App\Repositories\Lecturer\LecturerRepository;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Seminar\SeminarRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Title\TitleRepository;
use App\Services\Seminar\Validators\KaprodiSeminarValidator;
use App\Services\Seminar\Validators\StudentSeminarValidator;
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

    public function getSeminarByRole(string $role, $user)
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

    public function updateSeminarByRole(string $role, $id, $user, array $data)
    {
        $validator = match ($role) {
            'mahasiswa' => new SeminarValidatorContext(new StudentSeminarValidator()),
            'kaprodi' => new SeminarValidatorContext(new KaprodiSeminarValidator()),
        };

        $validatedData = $validator->validate($data);

        switch ($role) {
            case 'kaprodi':
                $seminar = $this->mainRepository->findOrFail($id);
                $this->authorizeKaprodiAccess($user->lecturer->id, $seminar->student_id);
                return $this->mainRepository->updateById($id, $validatedData);

            case 'mahasiswa':
                if (isset($validatedData['seminar_file'])) {
                    $validatedData['seminar_file'] = $this->storeFile($validatedData['seminar_file']);
                    $validatedData['status'] = "pending";
                }
                return $this->mainRepository->updateById($id, $validatedData);

            default:
                return null;
        }
    }


    public function findById(string $id)
    {
        $seminar = $this->mainRepository->findById($id);
        return $seminar;
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
        return $file->store('uploads/seminar', 'public');
    }
}
