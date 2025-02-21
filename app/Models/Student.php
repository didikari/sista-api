<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasUuids;
    protected $fillable = [
        'user_id',
        'study_program_id',
        'nim',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function guidances()
    {
        return $this->hasMany(Guidance::class, 'student_id');
    }

    public function title()
    {
        return $this->hasOne(Title::class);
    }
}
