<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasUuids;
    protected $fillable = [
        'payment_type',
        'payment_file',
        'student_id',
        'verified_by',
        'status',
        'verified_at',
    ];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->format('d-m-Y : H:i');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->setTimezone('Asia/Jakarta')->format('d-m-Y : H:i');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
