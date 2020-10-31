<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
class TblCategories extends Model
{
  //  use LogsActivity;
    
    protected $fillable = [
        'category_title'
    ];

    protected static $logAttributes = ['*'];
}
