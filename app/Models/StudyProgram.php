<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    use HasUuids;
    protected $fillable = [
        'department_name',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }
}
