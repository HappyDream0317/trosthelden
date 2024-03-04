<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Post.
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property string $message
 * @property int|null $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $title
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PostComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PostComment[] $commentsReplies
 * @property-read int|null $comments_replies_count
 * @property-read \App\Group $group
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Impression[] $impressions
 * @property-read int|null $impressions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereUserId($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withTrashed();
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id')->whereNull('parent_comment_id');
    }

    public function commentsReplies()
    {
        return $this->hasManyThrough(PostComment::class, 'App\PostComment', 'parent_comment_id');
    }

    public function reports()
    {
        return $this->morphMany(User::class, 'reportable');
    }

    public function impressions()
    {
        return $this->morphMany(Impression::class, 'impressionable');
    }
}
