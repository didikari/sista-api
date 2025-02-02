<?php

namespace App\Repositories\Exam;

use LaravelEasyRepository\Repository;

interface ExamRepository extends Repository
{
    public function getAll();
    public function getByStudent(string $studentId);
    public function getByDosen(string $dosenId);
    public function getByKaprodi($user);
    public function createExam(array $data);
    public function findByStudentId($studentId);
    public function updateByKaprodi($id, array $data);
}
