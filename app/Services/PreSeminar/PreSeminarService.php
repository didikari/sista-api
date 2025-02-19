<?php

namespace App\Services\PreSeminar;

use LaravelEasyRepository\BaseService;

interface PreSeminarService extends BaseService
{
    public function createPreSeminar(array $data, $studentId);
    public function getPreSeminarByRole(string $role, $user);
    public function updatePreSeminarByRole(string $role, $id, $user, array $data);
    public function findById(string $id);
}
