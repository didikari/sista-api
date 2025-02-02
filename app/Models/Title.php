<?php

namespace App\Models;

use App\Enums\TitleStatus;
use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasUuids;
    protected $table = 'titles';
    protected $fillable = [
        'student_id',
        'title',
        'abstract',
        'proposal_file',
        'supervisor_id',
        'status',
    ];

    protected $casts = [
        'status' => TitleStatus::class,
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor_id');
    }
}
