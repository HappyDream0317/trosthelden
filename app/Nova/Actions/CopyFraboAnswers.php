<?php

namespace App\Nova\Actions;

use App\Jobs\ProcessAllMatchings;
use App\MatchingAnswer;
use App\MatchingUserAnswer;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class CopyFraboAnswers extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return __('Copy Answers & Match');
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
        if ($models->count() > 1) {
            return Action::danger('Just one User can be selected!');
        }

        $userId = $models[0]->id;
        $userIdToCopyFrom = $fields['user_id'];

        // fetch the answers from the other user
        $answersFromOtherUser = MatchingUserAnswer::where('user_id', $userIdToCopyFrom)->get();

        if (!$answersFromOtherUser) {
            return Action::danger('The selected user has no answers to copy!');
        }

        // clean the users answers
        MatchingUserAnswer::where('user_id', $userId)->delete();

        // copy
        $answersFromOtherUser->each(function ($answer) use ($userId) {
            $copiedAnswer = new MatchingUserAnswer();
            $copiedAnswer->user_id = $userId;
            $copiedAnswer->answer_id = $answer->answer_id;
            $copiedAnswer->answer_text = $answer->answer_text;
            $copiedAnswer->save();
        });

        // consider for matching
        $user = User::find($userId);
        $user->matching_step = -1;
        $user->assignRole('mourner');
        $user->save();

        // match
        ProcessAllMatchings::dispatchAfterResponse(
            $userId,
            false,
            true
        );

        return Action::message('calculating matches ...');
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
            Select::make(__('User'), 'user_id')
                ->options(function () {
                    return User::all()->pluck('nickname', 'id');
                })
                ->rules('required'),
        ];
    }
}
