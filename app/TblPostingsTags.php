<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblPostingsTags extends Model
{
    protected $table = 'tbl_postings_tags';

    protected $guarded = ['id','created_at','updated_at'];
    
    public function Tags(){
        return $this->hasMany('App\TblTags','id','tag_id');
    }

   
}
