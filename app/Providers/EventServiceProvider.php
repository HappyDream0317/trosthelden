<?php

namespace App\Providers;

use App\Events\ChatMessageNew;
use App\Events\CommentNew;
use App\Events\FriendRequestConfirmation;
use App\Events\FriendRequestNew;
use App\Events\PostCommentDelete;
use App\Events\PostDelete;
use App\Events\PostNew;
use App\Events\RegisteredCompany;
use App\Events\RegisteredSupporter;
use App\Events\RegisteredNova;
use App\Listeners\DeleteFrontendPost;
use App\Listeners\DeleteFrontendPostComment;
use App\Listeners\LoginEventNotificationListener;
use App\Listeners\SendConfirmFriendRequestNotification;
use App\Listeners\SendFriendRequestNotification;
use App\Listeners\SignUpEventNotificationListener;
use App\Listeners\UpdateFrontendChatMessage;
use App\Listeners\UpdateFrontendComments;
use App\Listeners\UpdateFrontendPosts;
use App\Listeners\SetImpersonateCookie;
use App\Listeners\RemoveImpersonateCookie;
use App\Listeners\B2B\SendEmailVerificationNotification as SendCompanyEmailVerification;
use App\Listeners\SetDefaultRole;
use App\Listeners\RemoveDefaultRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification as SendStandardEmailVerification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use trosthelden\NovaImpersonate\Events\LeaveImpersonation;
use trosthelden\NovaImpersonate\Events\TakeImpersonation;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SetDefaultRole::class,
            SendStandardEmailVerification::class,
            SignUpEventNotificationListener::class,
        ],
        PostNew::class => [
            UpdateFrontendPosts::class,
        ],
        PostDelete::class => [
            DeleteFrontendPost::class,
        ],
        PostCommentDelete::class => [
            DeleteFrontendPostComment::class,
        ],
        CommentNew::class => [
            UpdateFrontendComments::class,
        ],
        ChatMessageNew::class => [
            UpdateFrontendChatMessage::class
        ],
        FriendRequestNew::class => [
            SendFriendRequestNotification::class,
        ],
        FriendRequestConfirmation::class => [
            SendConfirmFriendRequestNotification::class
        ],
        TakeImpersonation::class => [
            SetImpersonateCookie::class
        ],
        LeaveImpersonation::class => [
            RemoveImpersonateCookie::class
        ],
        RegisteredCompany::class=> [
            RemoveDefaultRole::class,
            SendCompanyEmailVerification::class
        ],
        RegisteredSupporter::class=> [
            RemoveDefaultRole::class
        ],
        RegisteredNova::class=> [
            SetDefaultRole::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
