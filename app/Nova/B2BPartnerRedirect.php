<?php

namespace App\Nova;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;

class B2BPartnerRedirect extends Resource
{
    public static $group = 'B2B';
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\B2BPartnerRedirect>
     */
    public static $model = \App\B2BPartnerRedirect::class;

    /**
     * Get the displayable name of the dashboard.
     *
     * @return string
     */
    public static function label()
    {
        return 'Affiliate Redirects';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'slug';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'slug', 'target'
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

            Text::make(__('Slug'), 'slug')
                ->sortable()
                ->rules('required', 'max:254')
                ->creationRules( 'unique:b2b_partner_redirects,slug')
                ->updateRules('unique:b2b_partner_redirects,slug,{{resourceId}}')
                ->fillUsing(function ($request, $model, $attribute) {
                    $model->{$attribute} = Str::slug($request->input($attribute), '-');
                }),

            URL::make(__('Target URL'), 'target')
                ->sortable()
                ->rules('required', 'max:4096')
                ->displayUsing(fn () => Str::limit($this->target)),

            Text::make('Redirect URL', fn () => url("/b2b/partner/{$this->slug}"))
                ->readonly()
                ->copyable(),

            BelongsTo::make(__('Partner'), 'b2bPartner', B2BPartner::class)
                ->nullable(),
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
