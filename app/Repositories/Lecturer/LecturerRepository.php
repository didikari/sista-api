<?php

namespace App\Repositories\Lecturer;

use LaravelEasyRepository\Repository;

interface LecturerRepository extends Repository
{
    public function getStudyProgramByLecturerId($lecturerId);
}
