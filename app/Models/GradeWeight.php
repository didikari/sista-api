<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GradeWeight extends Model
{
    use HasUuids;
    protected $fillable = [
        'role',
        'a1_weight',
        'a2_weight',
        'a3_weight',
        'a4_weight',
        'a5_weight',
        'a6_weight',
    ];
}
