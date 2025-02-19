<?php

namespace App\Services\GuidanceHistory;

use LaravelEasyRepository\BaseService;

interface GuidanceHistoryService extends BaseService
{
    public function getByRole(string $role, $user);
}
