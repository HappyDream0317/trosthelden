<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Referrer.
 *
 * @property int $id
 * @property int $user_id
 * @property string|null referrer
 * @property string|null referring_domain
 * @property string|null content
 * @property string|null campaign
 * @property string|null medium
 * @property string|null source
 * @property string|null keyword
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\User user
 * @mixin \Eloquent
 */
class Referrer extends Model
{
    protected $fillable = ['user_id', 'referrer', 'referring_domain', 'content', 'campaign', 'medium', 'source', 'keyword'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
