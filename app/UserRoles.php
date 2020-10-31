<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;

class UserRoles extends Model
{
  //  use LogsActivity;

    protected $fillable = ['user_id','role_id'];

   // protected static $logAttributes = ['*'];
    
    public function Roles(){
        return $this->hasOne('App\Roles','id','role_id');
    }
   
}
