<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('resend-verification-email', 'Auth\VerificationController@resendVerificationEmail');
Route::post('resend-change-email', 'Auth\VerificationController@resendChangeEmailVerification');
Route::post('register', 'Auth\RegisterController@register');
Route::post('verify/{id}/{hash}', 'Auth\VerificationController@verify');

Route::post('forgot-password', 'Auth\ForgotPasswordController@forgotPassword');
Route::post('reset-password', 'Auth\ForgotPasswordController@reset');

// FAQ
Route::get('faq', 'FaqQuestionController@getFAQ');

// Enviroment
Route::post('log/error', 'LoggingController@error');
Route::post('env', 'WebController@getEnvVars');

// Authentication
Route::prefix('auth/access-tokens')->group(function () {

    Route::post('/', 'Auth\AccessTokensController@store');
    Route::post('/remote', 'Auth\AccessTokensController@remote');
    Route::delete('/{access_token}', 'Auth\AccessTokensController@destroy')->middleware(['auth:api']);

});

// Referrer
Route::prefix('referrer')->group(function () {
    Route::post('/', 'ReferrerController@store');
    Route::get('/check', 'ReferrerController@checkHeaders');
});

// Remote - Marketing Page
Route::prefix('remote')->group(function () {
    Route::post('/register', 'Auth\RemoteController@register');
    Route::post('/login', 'Auth\RemoteController@login');
});

// Payment
Route::prefix('payments')->group(function () {
    Route::post('/order-succeeded', 'PaymentController@orderSucceeded');
    Route::post('/customer-created', 'PaymentController@customerCreated');
    Route::post('/contract-created', 'PaymentController@contractCreated');
    Route::post('/contract-cancelled', 'PaymentController@contractCancelled');
    Route::post('/contract-changed', 'PaymentController@contractChanged');
    Route::post('/send-mail', 'PaymentController@sendMail');
    Route::get('/customer/status/{contractId}', 'PaymentController@checkOrder');
    Route::get('/plans/{variantId}', 'PaymentController@planVariantsInfo');
    Route::post('/customer/search', 'PaymentController@getCustomerIdByAttr');
    Route::post('/order', 'PaymentController@createOrder');
    Route::post('/countries', 'PaymentController@getCountries');
    Route::post('/plans/standards', 'PaymentController@getStandardPlansInfo');
    Route::post('/plans/{variantId}/is-b2b', 'PaymentController@isB2BProduct');
    Route::post('/plans/{variantId}/is-flatrate', 'PaymentController@isFlatrateProduct');
    Route::post('/plans/{variantId}/is-standard', 'PaymentController@isStandardProduct');
    Route::post('/plans/{variantId}/is-coupon', 'PaymentController@isCouponProduct');


    Route::middleware(['auth:api'])->group(function () {
        Route::get('/customer/{userId}', 'PaymentController@getCustomerData');
        Route::get('/contract-cancellation/preview/{userId}', 'PaymentController@contractCancellationPreview');
        Route::post('/contract-cancellation', 'PaymentController@contractCancellation');
    });
});

// Guide
Route::prefix('guide')->middleware(['auth:api'])->group(function () {
    Route::get('/', 'RegistrationGuide@step');
    Route::post('/register-as-supporter', 'RegistrationGuide@registerAsSupporter');
    Route::post('/confirm-as-mourner', 'RegistrationGuide@confirmAsMourner');
    Route::post('/{stepNo}', 'RegistrationGuide@step');
});

//Comments
Route::prefix('comment')->middleware(['auth:api'])->group(function () {
    Route::post('{comment_id}/report', 'ReportController@reportPostComment');
    Route::get('user/latest', 'PostController@getLatestRepliesPerUser');
});

//Posts
Route::prefix('post')->middleware(['auth:api'])->group(function () {
    Route::post('/', 'PostController@savePost');
    Route::get('/{group_id}', 'PostController@getPosts');
    Route::get('/{post_id}', 'PostController@getPost');
    Route::post('/{post_id}/comments', 'PostCommentController@saveComment');
    Route::get('/{post_id}/comments', 'PostCommentController@getComments');
    Route::post('/{post_id}/report', 'ReportController@reportPost');
    Route::get('/user/similar-mourning/latest', 'PostController@getSimilarMourningPosts');
    Route::get('/user/friends/latest', 'PostController@getLatestFriendPosts');
});

// Chat
Route::prefix('chat')->middleware(['auth:api'])->group(function () {
    Route::get('/partners', 'ChatMessageController@partners');
    Route::get('/info/{recipient_id}', 'ChatMessageController@info');
    Route::post('/{chat_id}/send', 'ChatMessageController@sendMessage');
    Route::get('/unread', 'ChatMessageController@getNewMessageCount');
    Route::get('/unreadByChat', 'ChatMessageController@getNewMessageCountByChat');
    Route::post('/message/{msg_id}/read', 'ChatMessageController@markMsgRead');
    Route::post('/messages/{chat_id}/{partner_id}/read', 'ChatMessageController@markMessagesAsRead');
});

//Profile
Route::prefix('profile')->middleware(['auth:api'])->group(function () {

    Route::put('/visibility/set', 'ProfileController@setVisibilities');
    Route::get('/init', 'ProfileController@getProfileInit');
    Route::post('/last_seen', 'ProfileController@storeLastSeen');
    Route::get('/{user_id}/init', 'ProfileController@getForeignProfileInit');
    Route::post('/{user_id}/report', 'ReportController@reportProfile');
    Route::get('/{user_id}/questions/answers', 'ProfileQuestionController@getAllAnswersFromSpecificUser');
    Route::get('/{user_id}/reason', 'ProfileController@getAnswersForReasonArea');
    Route::get('/{user_id}/getInfoListingElement', 'ProfileController@getInfoListingElement');

    Route::prefix('questions')->group(function () {
        Route::get('/', 'ProfileQuestionController@getProfileQuestions');
        Route::get('/answers', 'ProfileQuestionAnswerController@getAllAnswersFromUser');
        Route::put('/{question_id}/answer', 'ProfileQuestionAnswerController@saveAnswer');
    });

    Route::prefix('progress')->group(function () {
        Route::get('/', 'ProfileController@getProgress');
        Route::put('/update', 'ProfileController@updateProgress');
    });

    Route::prefix('motto')->group(function () {
        Route::get('/', 'ProfileController@getMottoForPanel');
        Route::put('/saveMotto', 'ProfileController@saveMotto');
    });

    Route::prefix('avatar')->group(function () {
        Route::post('/', 'ProfileController@uploadAvatar');
        Route::delete('/', 'ProfileController@deleteAvatar');
    });
});

//Groups
Route::prefix('group')->middleware(['auth:api'])->group(function () {
    Route::get('/', 'GroupsController@getAllGroups');
    Route::get('/{group_id}', 'GroupsController@getGroup');
});

// Settings
Route::prefix('settings')->middleware(['auth:api'])->group(function () {
    Route::get('/me', 'SettingsController@getMe');
    Route::post('/change-password', 'SettingsController@changePassword');
    Route::post('/change-email', 'SettingsController@changeEmail');
    Route::post('/change-nickname', 'SettingsController@changeNickname');
});

// User
Route::prefix('user')->middleware(['auth:api'])->group(function () {

    // Checkout
    Route::get('/{user_id}/partner-code', 'UserController@getPartnerCodePerId')->withoutmiddleware(['auth:api']);


    // Manage Account
    Route::post('/delete', 'UserController@deleteAccount');
    Route::post('/revoke-delete', 'UserController@revokeDeleteAccount');
    Route::post('/reset-matching', 'UserController@resetMatching');


    Route::post('/has-seen', 'UserController@saveHasSeen');
    Route::post('/set-premium-paywall-seen', 'UserController@setPremiumPaywallSeen');

    //Matches
    Route::prefix('matching')->group(function () {
        Route::get('/', 'UserController@getMatches');
        Route::post('/status', 'UserController@setMatchingStatus');
        Route::get('/new', 'UserController@getCountOfNewMatchings');
    });

    // Premium
    Route::prefix('premium')->group(function () {
        Route::get('/', 'UserController@isPremium');
        Route::post('/', 'UserController@setPremium');
    });

    //Notifications
    Route::prefix('notifications')->group(function () {
        Route::post('/', 'UserController@saveNotifications');
        Route::get('/', 'UserController@getNotifications');
    });

    Route::prefix('friend')->group(function () {

        //FriendRequest
        Route::prefix('request')->group(function () {
            Route::post('/send/{foreign_user_id}', 'UserController@sendFriendRequest');
            Route::put('/retract/{foreign_user_id}', 'UserController@retractFriendRequest');
            Route::put('/deny/{foreign_user_id}', 'UserController@denyFriendRequest');
            Route::put('/accept/{foreign_user_id}', 'UserController@acceptFriendRequest');
        });

        //FriendList
        Route::prefix('list')->group(function () {
            Route::get('/', 'UserController@getFriends');
            Route::put('/remove/{foreign_user_id}', 'UserController@removeFriend');
            Route::get('/invitation', 'UserController@getFriendRequests');
            Route::get('/invitation/new', 'UserController@getCountOfNewFriendRequests');
        });
    });

    //Watchlist
    Route::prefix('watchlist')->group(function () {
        Route::get('/get', 'UserController@getWatchlist');
        Route::put('/watch/{foreign_user_id}', 'UserController@addToWatchlist');
        Route::put('/remove/{foreign_user_id}', 'UserController@removeFromWatchlist');
        Route::get('/search/{foreign_user_id}', 'UserController@isOnWatchlist');
    });

    //Blocklist
    Route::prefix('blocklist')->group(function () {
        Route::get('/', 'UserController@getAllBlocklist');
        Route::get('/get', 'UserController@getBlocklist');
        Route::post('/block/{foreign_user_id}', 'UserController@addToBlocklist');
        Route::put('/remove/{foreign_user_id}', 'UserController@removeFromBlocklist');
        Route::get('/search/{foreign_user_id}', 'UserController@isOnBlocklist');
    });
});

//B2B
Route::prefix('b2b')->group(function () {

    Route::post('/user/store', 'B2B\UserController@store');
    Route::post('/user/unique', 'B2B\UserController@validateUser');
    Route::get('/partner/{id}/code', 'B2B\PartnerController@getCodeById');
    Route::get('/partner/redirect/{slug}', 'B2B\PartnerController@getRedirectBySlug');
    Route::get('/discount/{code}', 'B2B\DiscountController@getByCode');
    Route::post('/code/used', 'B2B\CodeController@markAsUsed');

    Route::post('/coupon/has-flatrate', 'B2B\CouponController@hasValidFlatrate');
    Route::post('/verify/hash', 'B2B\VerificationController@verify');
    Route::post('/verify/resend', 'B2B\VerificationController@resend');
    Route::post('/verify/reset', 'B2B\VerificationController@reset');

    Route::middleware(['auth:api', 'owner', 'can:company'])->group(function () {
        Route::get('/{user_id}/user', 'B2B\UserController@getByUserId');
        Route::get('/{user_id}/code/assigned', 'B2B\CodeController@getAssigned');
        Route::get('/{user_id}/code/not-assigned', 'B2B\CodeController@getNotAssigned');
        Route::post('/{user_id}/code/{id}/pdf', 'B2B\CodeController@getPDF');
        Route::get('/{user_id}/code/{id}/pdf/{name}', 'B2B\CodeController@showPDF')->name("b2b.show.code.pdf");
        Route::post('/{user_id}/code/{id}/description', 'B2B\CodeController@updateDescription');
        Route::post('/{user_id}/code/{id}/assigned', 'B2B\CodeController@updateAssigned');
        Route::post('/{user_id}/coupon/{id}/code', 'B2B\CouponController@generateCode');
        Route::get('/{user_id}/partner/{id}', 'B2B\PartnerController@getById');
    });
});



