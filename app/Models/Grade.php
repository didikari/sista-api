<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Grade extends Model
{
    use HasUuids;
    protected $fillable = [
        'gradable_id',
        'gradable_type',
        'lecturer_id',
        'role_in_activity',
        'a1',
        'a2',
        'a3',
        'a4',
        'a5',
        'a6',
        'status',
    ];

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function gradable(): MorphTo
    {
        return $this->morphTo();
    }
}
