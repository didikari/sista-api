<?php

namespace App\Services\Exam;

use LaravelEasyRepository\BaseService;

interface ExamService extends BaseService
{
    public function createExam(array $data, $studentId);
    public function updateExamByKaprodi(array $data, $id, $lecturerId);
    public function getExamsByRole(string $role, $user);
}
