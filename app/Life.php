<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Life extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function fileentry()
    {
        return $this->belongsTo('App\Fileentry');
    }
}
