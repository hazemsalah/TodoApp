<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
  protected $fillable = ['voteable_id', 'voteable_type', 'user_id', 'state'];

    public function voteable()
    {
        return $this->morphTo();
    }
}
