<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Report.
 *
 * @property int $id
 * @property int $user_id
 * @property int $reportable_id
 * @property string $reportable_type
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $reportable
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereReportableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 * @mixin \Eloquent
 */
class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reportable_id',
        'reportable_type',
        'reason',
    ];

    public function posts()
    {
        // return $this->morphedByMany()
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}
