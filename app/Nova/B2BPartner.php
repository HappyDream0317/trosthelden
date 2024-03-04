<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\HasMany;

class B2BPartner extends Resource
{

    public static $group = 'B2B';
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\B2BPartner>
     */
    public static $model = \App\B2BPartner::class;


    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Affiliate';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'code'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Code')
                ->hideWhenUpdating()
                ->default(function ($request) {
                    return Str::orderedUuid();
                }),

            BelongsTo::make(__('B2B User'), 'b2bUser', B2BUser::class)->nullable(),

            HasMany::make(__('Discounts'), 'b2bDiscount', B2BDiscount::class),

            HasMany::make(__('Redirects'), 'b2bRedirects', B2BPartnerRedirect::class),

            HasMany::make(__('Users'), 'users', User::class),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
