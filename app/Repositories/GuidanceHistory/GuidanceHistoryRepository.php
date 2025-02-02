<?php

namespace App\Repositories\GuidanceHistory;

use LaravelEasyRepository\Repository;

interface GuidanceHistoryRepository extends Repository
{
    public function createHistory(array $data);
}
