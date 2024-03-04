<?php

namespace App\Providers;

use App\Observers\PostCommentObserver;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use App\Post;
use App\PostComment;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Post::observe(PostObserver::class);
        PostComment::observe(PostCommentObserver::class);
        User::observe(UserObserver::class);
        Gate::define('company', fn(User $user) => $user->is_business_account);

        // Temporary URL for local storage driver
        Storage::disk('local')->buildTemporaryUrlsUsing(function ($path, $expiration, $options) {
            return URL::temporarySignedRoute(
                'local.temp',
                $expiration,
                array_merge($options, ['path' => $path])
            );
        });
    }
}
