<?php

namespace App\Models;

use App\Models\Traits\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasUuids;
    protected $table = 'personal_informations';
    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'date_of_birth',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
