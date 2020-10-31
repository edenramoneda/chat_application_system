<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use DB;
use Carbon\Carbon;
class TblPostings extends Model
{
    //use LogsActivity;

    protected $fillable = [
        'category_id','title','content','img','posted_by'
    ];

    protected static $logAttributes = ['*'];

    protected $appends = ['date_posted'];

    public function Categories(){
        return $this->hasOne('App\TblCategories','id','category_id');
    }
    public function Status(){
        return $this->hasOne('App\TblStatus','id','status_id');
    }
    public function PostTags(){
        return $this->hasMany('App\TblPostingsTags','posting_id','id');
    }

    public function tags(){
        return $this->belongsToMany( 'App\TblTags', 'App\TblPostingsTags', 'posting_id', 'tag_id');
    }

    public function IsExistsTags(){
        return DB::table('tbl_postings_tags')->where('posting_id', $this->id)->exists();
    }
    public function Author(){
        return $this->hasOne('App\User','id','posted_by');
    }
    public function getDatePostedAttribute(){
        $date = Carbon::parse($this->created_at);
        return $date->isoFormat('LLLL');  
    }
}