<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TblPostings;
use App\TblCategories;
use App\TblTags;
use App\User;
use App\Status;
class ActivityLog extends Model
{
    protected $table = 'activity_log';

    public function label(){
        if($this->description == 'created'){
            return 'Added';
        }else if($this->description == 'updated'){
            return 'Updated';
        }else if($this->description == 'softdeleted'){
            return 'Deleted';
        }
    }

    public function logname(){
        $posts = TblPostings::all()->where('id',$this->subject_id);
        $json_dec = json_decode($this->properties);
        if($this->subject_type == 'App\TblPostings'){
            foreach($posts as $posts){
                if($this->label() == 'Added'){
                    return $this->User->username . ' ' . $this->label() . ' new Post "' . $json_dec->attributes->title . '"';    
                }else if($this->label() == 'Updated'){
                    if($this->SoftDeleted()){
                        if(strcmp($json_dec->attributes->title, $json_dec->old->title) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the post title from "' . $json_dec->old->title .'" to "' . $json_dec->attributes->title .  '"';
                        }
                        else if(strcmp($json_dec->attributes->content, $json_dec->old->content) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the content of "' . $json_dec->attributes->title .  '"';
                        }
                        else if(strcmp($json_dec->attributes->img, $json_dec->old->img) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the image header of "' . $json_dec->attributes->title .  '"';
                        }
                        else if(strcmp($json_dec->attributes->status_id, $json_dec->old->status_id) !== 0){
                            $status_old = TblStatus::where('id',$json_dec->old->status_id)->first();
                            return $this->User->username .  ' ' . $this->label() . ' the status of "' . $json_dec->attributes->title .  '" from ' . $status_old->label . ' to ' . $posts->status->label;
                        }
                    }   
                }
                else if($this->label() == 'Deleted'){
                    return $this->User->username .  ' ' . $this->label() . ' the Post "' . $posts->title .'"';
                }                       
            }
        }
        else if($this->subject_type == 'App\TblCategories'){
            $categories = TblCategories::all()->where('id',$this->subject_id);
            foreach($categories as $category){
                if($this->label() == 'Added'){
                    return $this->User->username . ' ' . $this->label() . ' new Category "' . $json_dec->attributes->category_title . '"';    
                }else if($this->label() == 'Updated'){
                    if($this->SoftDeleted()){
                        if(strcmp($json_dec->attributes->category_title, $json_dec->old->category_title) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the category title from "' . $json_dec->old->category_title .'" to "' . $json_dec->attributes->category_title .  '"';
                        }
                    }   
                }
                else if($this->label() == 'Deleted'){
                    return $this->User->username .  ' ' . $this->label() . ' the Category "' . $category->category_title .'"';
                }
            }
        }
        else if($this->subject_type == 'App\TblTags'){
            $tags = TblTags::all()->where('id',$this->subject_id);
            foreach($tags as $tags){
                if($this->label() == 'Added'){
                    return $this->User->username . ' ' . $this->label() . ' new tag "' . $json_dec->attributes->tag . '"';    
                }else if($this->label() == 'Updated'){
                    if($this->SoftDeleted()){
                        if(strcmp($json_dec->attributes->tag, $json_dec->old->tag) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the tag title from "' . $json_dec->old->tag .'" to "' . $json_dec->attributes->tag .  '"';
                        }
                    }   
                }
                else if($this->label() == 'Deleted'){
                    return $this->User->username .  ' ' . $this->label() . ' the tag "' . $tags->tag .'"';
                }
            }
        }
        else if($this->subject_type == 'App\User'){
            $user = User::all()->where('id',$this->subject_id);
            foreach($user as $user){
                if($this->label() == 'Added'){
                    return $this->User->username . ' ' . $this->label() . ' new user "' . $user->username . '"';    
                }
                else if($this->label() == 'Updated'){
                    if($this->SoftDeleted()){
                        if(strcmp($json_dec->tag, $json_dec->old->tag) !== 0){
                            return $this->User->username .  ' ' . $this->label() . ' the tag title from "' . $json_dec->old->tag .'" to "' . $json_dec->attributes->tag .  '"';
                        }
                    }   
                }
            }
        }
    }

    public function User(){
        return $this->hasOne('App\User','id','causer_id');
    }
    

    public function SoftDeleted(){
        $json_dec = json_decode($this->properties);

        if($json_dec->attributes->is_deleted == 0){
            return true;
        }
    }

    public function created_at(){
        return \Carbon\Carbon::parse($this->created_at)->isoFormat('LL LTS');
    }
}
