<?php

namespace App\Repositories\Guidance;

use LaravelEasyRepository\Repository;

interface GuidanceRepository extends Repository
{
    public function createGuidance(array $data);
    public function updateGuidance($id, array $data);
    public function updateBySupervisor($id, array $data);
    public function findByStudentId($studentId);
}
