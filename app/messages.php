<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Messages;
class Messages extends Model
{
    
    protected $fillable = [
        'user_id','message','sent_to'
    ];

    public function user(){

        return $this->belongsTo(User::class);
        
    }
}
