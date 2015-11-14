<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function fileentry()
    {
        return $this->belongsTo('App\Fileentry');
    }
    public function effects()
    {
        return $this->hasMany('App\EffectOfGuide');
    }
}
