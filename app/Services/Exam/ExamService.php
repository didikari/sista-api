<?php

namespace App\Services\Exam;

use LaravelEasyRepository\BaseService;

interface ExamService extends BaseService
{
    public function createExam(array $data, $studentId);
    public function getExamsByRole(string $role, $user);
    public function findById(string $id);
    public function updateExamByRole(string $role, $id, $user, array $data);
}
