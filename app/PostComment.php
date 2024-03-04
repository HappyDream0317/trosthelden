<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PostComment.
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property int|null $parent_comment_id
 * @property string $comment
 * @property int|null $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|PostComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Impression[] $impressions
 * @property-read int|null $impressions_count
 * @property-read \App\Post $post
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereParentCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostComment whereUserId($value)
 * @mixin \Eloquent
 */
class PostComment extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withTrashed();
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function reports()
    {
        return $this->morphMany(User::class, 'reportable');
    }

    public function impressions()
    {
        return $this->morphMany(Impression::class, 'impressionable');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'parent_comment_id');
    }
}
