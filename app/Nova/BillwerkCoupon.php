<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BillwerkCoupon extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\BillwerkCoupon::class;

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
        'id', 'period', 'code'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Date::make(__('Sent At'), 'sent_at')->sortable()
                ->hideWhenCreating(),
            Select::make(__('Period'), 'period')->options([
                '1m' => '1 Month',
                '3m' => '3 Months',
                '6m' => '6 Months',
            ]),
            File::make(__('File'), 'file')
                ->download(function ($request, $model, $disk, $value) {
                    return Storage::disk('public')->download($value, $model->code);
                }),
            Text::make(__('Code'), 'code')->sortable()
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
        return [];
    }
}
