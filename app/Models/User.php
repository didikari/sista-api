<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Relasi dengan model Lecturer
     */
    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }


    // /**
    //  * Relasi dengan model Title
    //  */
    // // public function title()
    // // {
    // //     return $this->hasOne(Title::class);
    // // }

    // /**
    //  * Relasi dengan model PersonalInformation
    //  */
    // public function personalInformation()
    // {
    //     return $this->hasOne(PersonalInformation::class);
    // }

    // /**
    //  * Relasi dengan model Payment
    //  */
    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }

    // /**
    //  * Relasi dengan model Guidance
    //  */
    // public function guidances()
    // {
    //     return $this->hasMany(Guidance::class);
    // }

    // /**
    //  * Relasi dengan model PreSeminar
    //  */
    // // public function preSeminars()
    // // {
    // //     return $this->hasMany(PreSeminar::class);
    // // }

    // // /**
    // //  * Relasi dengan model Seminar
    // //  */
    // // public function seminars()
    // // {
    // //     return $this->hasMany(Seminar::class);
    // // }

    // // /**
    // //  * Relasi dengan model Exam
    // //  */
    // // public function exams()
    // // {
    // //     return $this->hasMany(Exam::class);
    // // }

    // /**
    //  * Relasi dengan model Grade
    //  */
    // public function grades()
    // {
    //     return $this->hasMany(Grade::class);
    // }

    // /**
    //  * Relasi dengan model GuidanceHistory
    //  */
    // public function guidanceHistories()
    // {
    //     return $this->hasMany(GuidanceHistory::class);
    // }
}
