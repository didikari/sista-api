<?php

namespace App\Models;

use App\Enums\GuidanceStatus;
use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Guidance extends Model
{
    use HasUuids;
    protected $fillable = [
        'student_id',
        'supervisor_id',
        'proposal_file',
        'status',
        'guidance_date',
    ];

    protected $casts = [
        'status' => GuidanceStatus::class,
    ];


    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Lecturer::class, 'supervisor_id');
    }
}
