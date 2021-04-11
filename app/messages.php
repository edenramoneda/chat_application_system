<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
class Messages extends Model
{
    protected $fillable = [
        'user_id','message','sent_to'
    ];
    protected $appends = ['message_sent'];
    
    public function user(){

        return $this->belongsTo(User::class);
        
    }

    public function getMessageSentAttribute(){
        $date = Carbon::parse($this->created_at);
        return $date->isoFormat('MMM D, YYYY h:mm:ss a'); 
        // $date = Carbon::parse($this->created_at);
        // return $date->isoFormat('x'); 
    }
}