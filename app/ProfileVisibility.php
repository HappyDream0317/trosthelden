<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileMotto.
 *
 * @property int $id
 * @mixin \Eloquent
 */
class ProfileVisibility extends Model
{
    protected $table = "profile_visibilities";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
