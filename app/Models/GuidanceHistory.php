<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Carbon\Carbon;
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

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->format('d-m-Y : H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->format('d-m-Y : H:i');
    }
}
