<?php

namespace App\Nova;

use App\Nova\Actions\CopyFraboAnswers;
use App\Nova\Actions\ForceFriendsRelationship;
use App\Nova\Actions\ForcePremium;
use App\Nova\Actions\NonForcePremium;
use App\Nova\Actions\RefreshUserMatches;
use App\Nova\Actions\ResendEmailConfirmation;
use App\Nova\Filters\ConfirmedEmailUsers;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use trosthelden\NovaImpersonate\Impersonate;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\MorphToMany;

class User extends Resource
{
    public static $group = 'User Handling';
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\User';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'nickname';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'nickname', 'firstname', 'lastname', 'email',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Gravatar::make(),

            Text::make('Nickname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make(__('Name'), 'firstname')
                ->sortable()
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make(__('Last name'), 'lastname')
                ->sortable()
                ->rules('max:255')
                ->hideFromIndex(),

            Text::make(__('E-Mail'), 'email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Boolean::make('E-Mail-Adresse bestätigt', function () {
                return $this->email_verified_at !== null;
            })
                ->hideWhenCreating()
                ->hideWhenUpdating()
            ,

            Text::make('Frabo status', 'matching_step')
                ->hideWhenUpdating()
                ->hideWhenCreating(),

            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', 'string', 'min:8')
                ->updateRules('nullable', 'string', 'min:8'),

            DateTime::make("Erstellt am", 'created_at')
                ->exceptOnForms()
                ->readonly()
                ->sortable(),

            DateTime::make("Zuletzt online", 'last_seen_at')
                ->exceptOnForms()
                ->readonly()
                ->sortable(),

            DateTime::make("Verified at", 'email_verified_at')
                ->hideFromIndex()
                ->sortable(),

            Boolean::make(__('Nova access'), 'has_nova_access')
                ->trueValue(1)
                ->falseValue(0),

            Boolean::make(__('Premium'), 'is_premium')
                ->trueValue(1)
                ->falseValue(0),

            Boolean::make(__('Force Premium'), 'force_premium')
                ->trueValue(1)
                ->falseValue(0),

            DateTime::make(__('Premium Contract Cancellation'), 'cancellation_at')
                ->hideFromIndex()
                ->sortable(),

            DateTime::make(__('Premium Contract End'), 'premium_end_at')
                ->onlyOnDetail()
                ->readonly()
                ->sortable(),

            BelongsTo::make(__('Affiliate'), 'b2bPartner', B2BPartner::class)
            ->nullable(),

            Impersonate::make($this)->withMeta([
                'redirect_to' => '/dashboard'
            ]),

            MorphToMany::make('Roles', 'roles', \Vyuldashev\NovaPermission\Role::class),

            HasMany::make(__('Comments'), 'comments', PostComment::class),

            HasOne::make(__('B2B User'), 'b2bUser', B2BUser::class),

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
        return [
            new ConfirmedEmailUsers()
        ];
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
            new ResendEmailConfirmation(),
            new RefreshUserMatches(),
            new ForceFriendsRelationship(),
            new ForcePremium(),
            new NonForcePremium(),
            new CopyFraboAnswers()
        ];
    }
}
