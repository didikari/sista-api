<?php

namespace App\Services\Guidance;

use GuzzleHttp\Psr7\Request;
use LaravelEasyRepository\BaseService;

interface GuidanceService extends BaseService
{
    public function getGuidance(string $studentId);
    public function createGuidance(array $data);
    public function updateGuidance($id, array $data, $studentId);
    public function updateBySuperVisor($id, array $data, $supervisorId);
    public function findById(string $id);
}
