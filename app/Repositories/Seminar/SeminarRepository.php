<?php

namespace App\Repositories\Seminar;

use LaravelEasyRepository\Repository;

interface SeminarRepository extends Repository
{
    public function getAll();
    public function createSeminar(array $data);
    public function getByStudent(string $studentId);
    public function getByDosen(string $dosenId);
    public function getByKaprodi($user);
    public function findByStudentId($studentId);
    public function updateByKaprodi($id, array $data);
    public function updateById($id, array $data);
    public function findById(string $id);
}
