<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullname', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['updated_on'];
    /**
     * The attributes that should be cast to natisve types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function UserRoles(){
        return $this->hasOne('App\UserRoles','user_id','id');
    }

    public function getUpdatedOnAttribute(){
        $date = Carbon::parse($this->updated_at);
        return $date->isoFormat('MMMM Do YYYY, h:mm:ss');  

    }
}
