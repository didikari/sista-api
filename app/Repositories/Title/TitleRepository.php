<?php

namespace App\Repositories\Title;

use LaravelEasyRepository\Repository;

interface TitleRepository extends Repository
{
    public function getAll();
    public function getByStudent($studentId);
    public function getBySupervisor($supervisorId);
    public function createTitle(array $data);
    public function deleteTitle($titleId);
    public function updateTitle($titleId, array $data);
    public function updateBySupervisor($id, array $data);
    public function findByStudentId($studentId);
}
