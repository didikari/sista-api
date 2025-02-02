<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GuidanceHistory extends Model
{
    use HasUuids;
    protected $fillable = [
        'guidance_id',
        'guidance_date',
        'notes',
        'feedback',
        'status',
    ];

    public function guidance()
    {
        return $this->belongsTo(Guidance::class);
    }
}
