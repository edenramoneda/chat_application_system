<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Roles extends Model
{
    
    public function RoleList($role_id){
        return DB::table('roles')->where('id','<>',$role_id)->get();
    }
}
