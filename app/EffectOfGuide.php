<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EffectOfGuide extends Model
{
    protected $fillable = ['user_id', 'guide_id'];
}
