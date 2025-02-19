<?php

namespace App\Repositories\GuidanceHistory;

use LaravelEasyRepository\Repository;

interface GuidanceHistoryRepository extends Repository
{
    public function createHistory(array $data);
    public function getAll();
    public function getByStudent(string $guidanceId);
    public function getByDosen(string $guidanceId);
    public function getByKaprodi($user);
}
