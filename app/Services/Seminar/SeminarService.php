<?php

namespace App\Services\Seminar;

use LaravelEasyRepository\BaseService;

interface SeminarService extends BaseService
{
    public function createSeminar(array $data, $studentId);
    public function getExamsByRole(string $role, $user);
    public function updateSeminarByKaprodi(array $data, $id, $lecturerId);
}
