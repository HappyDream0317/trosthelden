<?php

namespace App\Nova;

use App\Nova\Actions\ExportReferrer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Titasgailius\SearchRelations\SearchesRelations;

class Referrer extends Resource
{
    use SearchesRelations;

    public static $group = 'User Handling';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Referrer::class;

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
        'referrer'
    ];

    /**
     * The relationship columns that should be searched.
     *
     * @var array
     */
    public static $searchRelations = [
        'user' => ['nickname', 'firstname', 'lastname', 'email',],
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
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('User'), 'user', User::class)
                ->readonly(),
            Text::make(__('Referrer'), 'referrer')->sortable(),
            Text::make(__('Campaign'), 'campaign')->sortable(),
            Text::make(__('Medium'), 'medium')->sortable(),
            Text::make(__('Source'), 'source')->sortable(),
            Text::make(__('Content'), 'content')->sortable(),
            Text::make(__('Referring domain'), 'referring_domain')->hideFromIndex()->sortable(),
            Text::make(__('Keyword'), 'keyword')->hideFromIndex()->sortable(),
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
        return [
            (new ExportReferrer())
        ];
    }
}
