<?php

namespace App\Repositories\Grade;

use LaravelEasyRepository\Repository;

interface GradeRepository extends Repository
{
    public function createForGradable(array $data);
    public function findByGradableAndUser(string $gradableId, string $lecturerId, string $role);
    public function updateForGrade(string $id, array $data);
}
