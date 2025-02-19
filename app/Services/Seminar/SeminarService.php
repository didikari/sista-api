<?php

namespace App\Services\Seminar;

use LaravelEasyRepository\BaseService;

interface SeminarService extends BaseService
{
    public function createSeminar(array $data, $studentId);
    public function getSeminarByRole(string $role, $user);
    public function findById(string $id);
    public function updateSeminarByRole(string $role, $id, $user, array $data);
}
