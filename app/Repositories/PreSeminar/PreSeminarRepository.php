<?php

namespace App\Repositories\PreSeminar;

use LaravelEasyRepository\Repository;

interface PreSeminarRepository extends Repository
{
    public function getAll();
    public function createPreSeminar(array $data);
    public function getByStudent(string $studentId);
    public function getByDosen(string $dosenId);
    public function getByKaprodi($user);
    public function findByStudentId($studentId);
    public function updateById($id, array $data);
    public function findById(string $id);
}
