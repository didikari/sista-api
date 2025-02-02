<?php

namespace App\Services\PreSeminar;

use LaravelEasyRepository\BaseService;

interface PreSeminarService extends BaseService
{
    public function createPreSeminar(array $data, $studentId);
    public function updatePreSeminarByKaprodi(array $data, $id, $lecturerId);
    public function getExamsByRole(string $role, $user);
}
