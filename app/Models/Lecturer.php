<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'study_program_id',
        'nidn',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
