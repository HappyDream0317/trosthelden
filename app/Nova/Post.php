<?php

namespace App\Nova;

use App\Nova\Metrics\NewPosts;
use App\Nova\Metrics\PostsPerDay;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Post extends Resource
{
    use SearchesRelations;

    public static $group = 'Community';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Post::class;

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
        'message',
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
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()
                ->sortable(),
            BelongsTo::make(__('Author'), 'author', User::class)
                ->readonly(),
            BelongsTo::make(__('Group'), 'group', Group::class)
                ->readonly(),
            Text::make(__('Message'), 'message')
                ->displayUsing(function ($comment) {
                    return Str::limit($comment, 500);
                })
                ->hideWhenCreating()
                ->hideWhenUpdating()
                ->asHtml(),
            Textarea::make(__('Message'), 'message')
                ->onlyOnForms(),

            HasMany::make(__('Post Comments'), 'comments', PostComment::class),
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
        return [
            new NewPosts,
            new PostsPerDay,
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
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
