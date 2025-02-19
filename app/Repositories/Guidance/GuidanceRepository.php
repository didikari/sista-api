<?php

namespace App\Repositories\Guidance;

use LaravelEasyRepository\Repository;

interface GuidanceRepository extends Repository
{
    // public function getGuidance(string $studentId);
    public function createGuidance(array $data);
    public function updateGuidance($id, array $data);
    public function updateBySupervisor($id, array $data);
    public function findByStudentId($studentId);
    public function findByDosenId(string $dosenId);
    public function findById(string $id);
}
