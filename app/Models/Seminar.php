<?php

namespace App\Models;

use App\Enums\EventStatus;
use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Seminar extends Model
{
    use HasUuids;
    protected $fillable = [
        'student_id',
        'title_id',
        'seminar_file',
        'supervisor_id',
        'examiner_id',
        'score',
        'submission_date',
        'seminar_date',
        'status'
    ];

    protected $casts = [
        'status' => EventStatus::class,
    ];


    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function supervisor()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor_id');
    }

    public function examiner()
    {
        return $this->belongsTo(Lecturer::class, 'examiner_id');
    }

    public function grades()
    {
        return $this->morphMany(Grade::class, 'gradable');
    }
}
