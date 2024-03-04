<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileProgress.
 *
*/
class ProfileProgress extends Model
{
    protected $table = 'profile_progress';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
