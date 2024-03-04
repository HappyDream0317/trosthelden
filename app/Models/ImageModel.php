<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\ImageModel.
 *
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ImageModel query()
 * @mixin \Eloquent
 */
class ImageModel extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('profile')
            ->width(500)
            ->height(500)
            ->sharpen(10);
    }
}
