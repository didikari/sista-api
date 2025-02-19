<?php

namespace App\Services\Grade;

use LaravelEasyRepository\BaseService;

interface GradeService extends BaseService
{
    public function createGrade(array $data, string $lecturerId, string $role);
    public function updateGrade(array $data, string $lecturerId, string $role, string $gradableId);
}
