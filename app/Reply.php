<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reply
 * @package App
 */
class Reply extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user (){
    return $this->belongsTo(User::class);
}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment (){
        return $this->belongsTo(Comment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies (){
        return $this->hasMany(Reply::class);
    }
}
