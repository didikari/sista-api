<?php

namespace App\Repositories\GradeWeight;

use LaravelEasyRepository\Repository;

interface GradeWeightRepository extends Repository
{
    public function getWeightByRole($roleName);
}
