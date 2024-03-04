<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class PostComment extends Resource
{
    use SearchesRelations;

    public static $group = 'Community';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\PostComment';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'author' => ['nickname'],
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()
                ->sortable(),
            BelongsTo::make(__('Author'), 'author', User::class),
            BelongsTo::make(__('Post'), 'post', Post::class),
            Text::make(__('Comment'), 'comment')
                ->displayUsing(function ($comment) {
                    return Str::limit($comment, 500);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->asHtml(),
            Textarea::make(__('Comment'), 'comment')
                ->onlyOnForms()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
