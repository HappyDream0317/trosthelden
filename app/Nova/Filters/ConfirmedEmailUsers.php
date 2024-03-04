<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Laravel\Nova\Http\Requests\NovaRequest;

class ConfirmedEmailUsers extends BooleanFilter
{
    const CONFIRMED_EMAIL = 'confirmed-email';
    const NOT_CONFIRMED_EMAIL = 'not-confirmed-email';

    public function __construct()
    {
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return 'Benutzerfilter';
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query
            ->when($this->isSelected($value, self::CONFIRMED_EMAIL), function ($q) {
                return $q->whereNotNull('email_verified_at');
            })
            ->when($this->isSelected($value, self::NOT_CONFIRMED_EMAIL), function ($q) {
                return $q->whereNull('email_verified_at');
            });
    }

    /***
     * Indicate when a filter is selected
     *
     * @param $value
     * @param $key
     * @return bool
     */
    public function isSelected($value, $key)
    {
        return isset($value[$key]) && $value[$key];
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            __('Bestätigte E-Mail') => self::CONFIRMED_EMAIL,
            __('E-Mail nicht bestätigt') => self::NOT_CONFIRMED_EMAIL,
        ];
    }
}
