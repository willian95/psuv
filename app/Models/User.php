<?php

namespace App\Models;

use App\Http\Traits\EmailVerificationTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes, EmailVerificationTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $guarded = [];

    protected $fillable = [
        'email',
        'name',
        'password',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'password_updated_at' => 'datetime',
    ];

    protected $guard_name = 'api';

    protected $appends = ['full_name'];

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

    public function password_reset(){
      return $this->hasOne(PasswordReset::class, 'email', 'email')->orderBy('created_at', 'desc');
    }

    public function municipio(){

        return $this->belongsTo(Municipio::class);

    }

}
