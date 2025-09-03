<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRolesAndPermissions;

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

    public function farms()
    {
        return $this->hasMany(Farm::class);
    }

    public function lender()
    {
        return $this->hasOne(Lender::class);
    }

    public function insurer()
    {
        return $this->hasOne(Insurer::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class);
    }
}
