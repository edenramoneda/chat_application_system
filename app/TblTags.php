<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;
class TblTags extends Model
{
   // use LogsActivity;
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'tbl_tags';

    protected static $logAttributes = ['*'];
}
