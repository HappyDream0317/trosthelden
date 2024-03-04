<?php

namespace App\Nova\Actions;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class NonForcePremium extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return __('Non Force-Premium');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        if ($models) {
            $models->each(function ($user) {
                $user->force_premium = false;
                $user->save();
            });
        }
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
        ];
    }
}
