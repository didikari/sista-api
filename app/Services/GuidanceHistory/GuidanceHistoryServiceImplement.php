<?php

namespace App\Services\GuidanceHistory;

use App\Repositories\Guidance\GuidanceRepository;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\GuidanceHistory\GuidanceHistoryRepository;

class GuidanceHistoryServiceImplement extends ServiceApi implements GuidanceHistoryService
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
    protected GuidanceHistoryRepository $mainRepository;
    protected GuidanceRepository $guidanceRepository;

    public function __construct(
        GuidanceHistoryRepository $mainRepository,
        GuidanceRepository $guidanceRepository
    ) {
        $this->mainRepository = $mainRepository;
        $this->guidanceRepository = $guidanceRepository;
    }

    public function getByRole(string $role, $user)
    {
        switch ($role) {
            case 'kaprodi':
                return $this->mainRepository->getByKaprodi($user);
            case 'dosen':
                return $this->mainRepository->getByDosen(
                    $this->guidanceRepository->findByDosenId($user->lecturer->id)->id ?? ''
                );
            case 'admin':
                return $this->mainRepository->getAll();
            case 'mahasiswa':
                return $this->mainRepository->getByStudent(
                    $this->guidanceRepository->findByStudentId($user->student->id)->id ?? ''
                );
            default:
                return null;
        }
    }
}
