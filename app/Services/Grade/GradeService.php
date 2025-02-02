<?php

namespace App\Services\Grade;

use LaravelEasyRepository\BaseService;

interface GradeService extends BaseService
{
    public function createGrade(array $data, string $lecturer, string $role);
    public function updateGrade(array $data, string $lecturer, string $role, string $gradableId);
}
