<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App
 */
class File extends Model
{
    /**
     * @var array
     */

    protected $fillable = array( 'path','comment_id','user_id');


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function comment (){
        return $this->belongsTo(Comment::class);

    }
}
