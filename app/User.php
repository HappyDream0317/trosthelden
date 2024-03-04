<?php

namespace App;

use App\Helpers\DeadReasonHelper;
use App\Helpers\FriendRequestHelper;
use App\Helpers\MournForHelper;
use App\Jobs\ProcessAllMatchings;
use App\Notifications\CustomChangeEmailNotification;
use App\Notifications\CustomResetPasswordNotification;
use App\Notifications\CustomVerifyEmail as StandardVerifyEmail;
use App\Notifications\B2B\CustomVerifyEmail as CompanyVerifyEmail;
use App\SendinBlue\SendinBlueHandler;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\User.
 *
 * @property int $id
 * @property string $nickname
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $matching_step -1 = finished, -2 = terminated
 * @property int|null $has_nova_access
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserFriendRequest[] $friendshipInvitations
 * @property-read int|null $friendship_invitations_count
 * @property-read mixed $age
 * @property string $avatar
 * @property-read mixed $death_reason
 * @property-read mixed $death_relation
 * @property-read int|null $friend_status
 * @property-read mixed $is_verified
 * @property-read bool|null $is_watched
 * @property-read bool|null $is_blocked
 * @property-read mixed $location
 * @property-read mixed $motto
 * @property-read mixed $protected_postal_code
 * @property-read mixed $sex
 * @property-read mixed $is_business_account
 * @property-read mixed $is_super_admin
 * @property-read mixed $is_mourner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MatchingUserAnswer[] $matchingAnswers
 * @property-read int|null $matching_answers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MatchingUserPartnerRanking[] $matchingPartners
 * @property-read int|null $matching_partners_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|Media[] $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PostComment[] $reportedPostComments
 * @property-read int|null $reported_post_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $reportedPosts
 * @property-read int|null $reported_posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read mixed $mourning_code
 * @property-read mixed $mourning_gender
 * @property-read mixed $mourning_text
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasNovaAccess($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMatchingStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use InteractsWithMedia;
    use HasApiTokens;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    const GENDER_QUESTION_ID = 7;
    const GENDER_ANSWER_ID_MALE = 80;
    const GENDER_ANSWER_ID_FEMALE = 81;
    const GENDER_ANSWER_ID_DIVERSE = 82;

    const BIRTHDAY_QUESTION_ID = 8;

    const REASON_OF_DEATH_QUESTION_ID = 5;
    const DATE_OF_DEATH_QUESTION_ID = 6;
    const RELATION_TO_DECEASED_QUESTION_ID = 2;

    const MOURNING_REASON_QUESTION_ID = 2;
    const MOURNING_FOR_QUESTION_ID = 3;
    const MOURNING_FOR_FAMILY_MEMBER_FREETEXT_ID = 34;
    const MOURNING_FOR_ANOTHER_PERSON_FREETEXT_ID = 40;

    const PLACE_OF_LIVING_QUESTION_ID = 9;
    const POSTAL_QUESTION_ID = 10;

    const FRIEND_REQUEST_SEND = 0;
    const FRIEND_REQUEST_RECEIVED = 1;
    const FRIEND_BEFRIENDED = 2;
    const FRIEND_UNKNOWN = 3;

    const DEFAULT_NOTIFICATION_VALUE = true;

    Protected string $guard_name ='web';

    protected $appends = [
        'sex',
        'age',
        'motto',
        'death_reason',
        'death_relation',
        'location',
        'protected_postal_code',
        'is_watched',
        'is_blocked',
        'is_verified',
        'friend_status',
        'friend_request',
        'is_business_account',
        'is_super_admin',
        'is_mourner',
        'mourning_code',
        'mourning_gender',
        'mourning_text',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'firstname', 'lastname', 'email', 'password', 'country', 'postal', 'age', 'sex', 'b2b_partner_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'firstname', 'lastname', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'requested_to_delete_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'cancellation_at' => 'datetime',
        'premium_end_at' => 'datetime',
        'notification_settings' => 'array',
        'has_seen' => 'array'
    ];

    public static function create(array $attributes = [])
    {
        $model = static::query()->create($attributes);
        static::initProfile($model->id);

        return $model;
    }

    /**
     * Determines if the User is a Super admin
     * @return bool
     */
    public function getIsSuperAdminAttribute() {
        return $this->hasRole('super-admin') || $this->has_nova_access;
    }

    public function getIsMournerAttribute() {
        return $this->hasRole('mourner');
    }

    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_users', 'user_id', 'group_id');
    }

    public function reportedPosts()
    {
        return $this->morphedByMany(Post::class, 'reportable');
    }

    public function reportedPostComments()
    {
        return $this->morphedByMany(PostComment::class, 'reportable');
    }

    public function matchingAnswers() : HasMany
    {
        return $this->hasMany(MatchingUserAnswer::class, 'user_id');
    }

    public function friendshipInvitations() : HasMany
    {
        return $this->hasMany(UserFriendRequest::class);
    }

    public function friendshipInvitationsBy() : HasMany
    {
        return $this->hasMany(UserFriendRequest::class, 'added_user_id');
    }

    public function matchingPartners() : HasMany
    {
        return $this->hasMany(MatchingUserPartnerRanking::class, 'user_id')
        ->orderBy('rank', 'ASC')
        ->orderBy('weight', 'DESC');
    }

    public function matchingPartnersBy() : HasMany
    {
        return $this->hasMany(MatchingUserPartnerRanking::class, 'partner_id')
            ->orderBy('rank', 'ASC')
            ->orderBy('weight', 'DESC');
    }

    public function profileProgress() : HasOne
    {
        return $this->hasOne(ProfileProgress::class);
    }

    public function profileMotto() : HasOne
    {
        return $this->hasOne(ProfileMotto::class);
    }

    public function profileVisibility() : HasOne
    {
        return $this->hasOne(ProfileVisibility::class);
    }

    public function profileQuestionAnswers() : HasMany
    {
        return $this->hasMany(ProfileQuestionAnswer::class);
    }

    public function watchlistItems() : HasMany
    {
        return $this->hasMany(UserWatchListItem::class);
    }

    public function watchedByItems() : HasMany
    {
        return $this->hasMany(UserWatchListItem::class, 'watched_user_id');
    }

    public function blockListItems() : HasMany
    {
        return $this->hasMany(UserBlockListItem::class);
    }

    public function blockedByListItems() : HasMany
    {
        return $this->hasMany(UserBlockListItem::class, 'blocked_user_id');
    }

    public function friends() : HasMany
    {
        return $this->hasMany(UserFriend::class);
    }

    public function friendsBy() : HasMany
    {
        return $this->hasMany(UserFriend::class, 'foreign_user_id');
    }

    public function friendRequests() : HasMany
    {
        return $this->hasMany(UserFriendRequest::class);
    }

    public function friendRequestsBy() : HasMany
    {
        return $this->hasMany(UserFriendRequest::class, 'added_user_id');
    }

    public function actionEvents() : HasMany
    {
        return $this->hasMany(ActionEvent::class);
    }

    public function reports() : HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function posts() : HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments() : HasMany
    {
        return $this->hasMany(PostComment::class);
    }

    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    public function chatConnections()
    {
        return ChatConnection::whereJsonContains('participants', $this->getKey());
    }

    public function referrer() : HasOne
    {
        return $this->hasOne(Referrer::class);
    }
    
    
    public function b2bUser() : HasOne
    {
        return $this->hasOne(B2BUser::class);
    }
    
    public function b2bCodes() : HasMany
    {
        return $this->hasMany(B2BCode::class);
    }
    
    public function b2bPartner()
    {
        return $this->belongsTo(B2BPartner::class, 'b2b_partner_id');
    }


    /**
     *
     * @return bool
     */
    public function canNovaImpersonate() : bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function canNovaBeImpersonated() : bool
    {
        // Let's be able to impersonate everybody...
        // return $this->has_nova_access === null || $this->has_nova_access === 0;
        return true;
    }

    /**
     * Delete functions
     */

    /**
     * Set all attributes to null
     * @param $except array
     */
    public function nullAttributes(array $except = [])
    {
        $except = array_merge($except, ['id', 'deleted_at']);

        foreach ($this->attributes as $attribute => $value) {
            if (in_array($attribute, $except)) {
                continue;
            }

            $this->attributes[$attribute] = null;
        }

        $this->save();
    }


    /**
     * Delete all user relations except the relations with post, comments and chat messages.
     */
    public function deleteNotRequireRelations()
    {
        $this->profileProgress()->delete();
        $this->profileMotto()->delete();
        $this->profileVisibility()->delete();
        $this->profileQuestionAnswers()->delete();
        $this->watchlistItems()->delete();
        $this->watchedByItems()->delete();
        $this->blockListItems()->delete();
        $this->blockedByListItems()->delete();
        $this->friends()->delete();
        $this->friendsBy()->delete();
        $this->friendRequests()->delete();
        $this->friendRequestsBy()->delete();
        $this->matchingPartners()->delete();
        $this->matchingPartnersBy()->delete();
        $this->matchingAnswers()->delete();
        $this->friendshipInvitations()->delete();
        $this->friendshipInvitationsBy()->delete();
        $this->groups()->delete();
        $this->actionEvents()->delete();
        $this->reports()->delete();
    }

    /**
     * This function is meant for restarting the frabo in the user settings
     */
    public function resetMatching()
    {
        $this->matchingPartners()->delete();
        $this->matchingPartnersBy()->delete();
        $this->matchingAnswers()->delete();
    }

    //Überschreibe die Funktion um ein CustomEmail-Foramt zu nutzen
    public function sendEmailVerificationNotification()
    {
        $this->notify(new StandardVerifyEmail);
    }

    public function sendCompanyEmailVerificationNotification()
    {
        $this->notify(new CompanyVerifyEmail);
    }

    public function sendChangeEmailNotification()
    {
        $this->notify(new CustomChangeEmailNotification);
    }

    public function sendEmailForgotPasswordNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function canSendNotification($notification)
    {
        if (!isset($this->notification_settings[$notification])) {
            return self::DEFAULT_NOTIFICATION_VALUE;
        }

        return $this->notification_settings[$notification];
    }

    public function hasSeenElement($hasSeenElement)
    {
        return isset($this->has_seen[$hasSeenElement]);
    }

    public function hasSeenElementByName($hasSeenElement)
    {
        return ($this->has_seen)? in_array($hasSeenElement, $this->has_seen) : null;
    }

    public static function initProfile(int $userId): void
    {
        DB::table('profile_visibilities')
            ->updateOrInsert(
                ['user_id' => $userId]
            );

        DB::table('profile_mottoes')
            ->updateOrInsert(
                ['user_id' => $userId]
            );

        $profile_questions = DB::table('profile_questions')->get();

        $insert_array = [];
        foreach ($profile_questions as $question) {
            $insert_array[] = ['user_id' => $userId, 'profile_question_id' => $question->id];
        }

        DB::table('profile_question_answers')->insert($insert_array);
    }

    public function getSexAttribute()
    {
        $userAnswer = MatchingUserAnswer::where('user_id', $this->id)
        ->whereHas('answer', function ($q) {
            $q->where('question_id', self::GENDER_QUESTION_ID);
        })
        ->with(['answer' => function ($a) {
            $a->select('id');
        }])
        ->first();

        if (!$userAnswer) {
            return '';
        }

        switch ($userAnswer->answer->id) {
          case self::GENDER_ANSWER_ID_MALE:
            return 'm';
          case self::GENDER_ANSWER_ID_FEMALE:
            return 'w';
          default:
            return 'd';
        }
    }

    public function getIsBlockedAttribute(): ?bool
    {
        if (!Auth::check() || Auth::id() === $this->id) {
            return null;
        }
        $user_id = Auth::id();
        $return = UserBlockListItem::where(['user_id' => $user_id, 'blocked_user_id' => $this->id])->first();
        if ($return) {
            return true;
        }
        return false;
    }

    public function getIsWatchedAttribute(): ?bool
    {
        if (!Auth::check() || Auth::id() === $this->id) {
            return null;
        }
        $user_id = Auth::id();
        $return = UserWatchListItem::where(['user_id' => $user_id, 'watched_user_id' => $this->id])
        ->first();
        if ($return) {
            return true;
        }

        return false;
    }

    public function getFriendStatusAttribute(): ?int
    {
        if (!Auth::check() || Auth::id() === $this->id) {
            return null;
        }

        // The current user, not $this
        $user_id = Auth::id();

        if ($user_id === $this->id) {
            return null;
        }

        if ($this->isFriendsWith($user_id)) {
            /**
             * Hotfix: EnsureAllFriendRequestsAreDeleted
             * TODO: Check if we can remove this deleteAllFriendRequests in the future
             */
            FriendRequestHelper::deleteAllFriendRequests($this->id, $user_id);

            return self::FRIEND_BEFRIENDED;
        }

        if ($this->hasSentFriendRequestTo($user_id)) {
            return self::FRIEND_REQUEST_RECEIVED;
        }

        if ($this->hasReceivedFriendRequestFrom($user_id)) {
            return self::FRIEND_REQUEST_SEND;
        }

        return self::FRIEND_UNKNOWN;
    }


    public function getFriendRequestAttribute()
    {
        if ($this->getFriendStatusAttribute() === null) {
            return null;
        }

        // The current user, not $this
        $user_id = Auth::id();

        if ($sent_friend_request = $this->hasSentFriendRequestTo($user_id)) {
            return $sent_friend_request;
        }

        if ($recieved_friend_request = $this->hasReceivedFriendRequestFrom($user_id)) {
            return $recieved_friend_request;
        }

        return null;
    }

    public function getIsVerifiedAttribute()
    {
        $return = $this->email_verified_at !== null;

        return $return;
    }

    public function getDeathReasonAttribute($value)
    {
        return $this->reasonOfDeath();
    }

    public function getDeathRelationAttribute()
    {
        return $this->relationToDeceased();
    }

    public function getMourningCodeAttribute()
    {
        return $this->getMourningCode();
    }

    public function getMourningGenderAttribute()
    {
        return $this->getMourningForGender();
    }

    public function getMourningTextAttribute()
    {
        return $this->getMourningForText();
    }

    public function getLocationAttribute()
    {
        $return = MatchingUserAnswer::where('user_id', $this->id)
        ->whereHas('answer', function ($q) {
            $q->where('question_id', self::PLACE_OF_LIVING_QUESTION_ID);
        })
        ->with([
            'answer' => function ($a) {
                $a->select('id', 'answer');
            },
        ])
        ->first();

        if ($return) {
            return $return->answer->answer;
        }

        return null;
    }

    public function getProtectedPostalCodeAttribute()
    {
        $return = MatchingUserAnswer::where('user_id', $this->id)
        ->whereHas('answer', function ($q) {
            $q->where('question_id', self::POSTAL_QUESTION_ID);
        })
        ->first();

        if ($return) {
            $postal = $return->answer_text;
            try { // qnd, sorry ;)
                $postal = substr($postal, 0, 2) . str_repeat('x', strlen($postal) - 2);
            } catch (\Throwable $th) {
                $postal = null;
            }
            return $postal;
        }
        return null;
    }

    public function getMottoAttribute()
    {
        $motto = ProfileMotto::where('user_id', $this->id)->get()->first();

        if (!$motto) {
            return '';
        }

        return $motto->text;
    }

    public function getAgeAttribute()
    {
        $userAnswer = MatchingUserAnswer::where('user_id', $this->id)
        ->whereHas('answer', function ($q) {
            $q->where('question_id', self::BIRTHDAY_QUESTION_ID);
        })
        ->first();
        if (!$userAnswer) {
            return null;
        }

        $userAnswer = json_decode($userAnswer->answer_text);

        $dateOfBirth = new DateTime($userAnswer->year . '-' . ($userAnswer->month ?: 1) . '-' . ($userAnswer->day ?: 1));

        return (new DateTime())->diff($dateOfBirth)->format('%y');
    }

    /*
        public function saveMediaFile(Request $request)
        {
            if(isset($request['profile_image']))
            {
                $this->clearMediaCollection('profile_images');
                $this->addMediaFromRequest('profile_image')
                    ->usingName('avatar')
                    ->toMediaCollection('profile_images');
            }
        }
    */

    public function registerMediaCollections(): void
    {
        $this
        ->addMediaCollection('profile_images')
        ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('profile')
        ->fit(Manipulations::FIT_CONTAIN, 500, 500);
    }

    public function isInitialized(): bool
    {
        $visibilities = DB::table('profile_visibilities')
        ->select('user_id')
        ->where('user_id', $this->id)
        ->first();
        $motto = DB::table('profile_mottoes')
        ->select('user_id')
        ->where('user_id', $this->id)
        ->first();

        return $visibilities && $motto;
    }

    /**
     * Is this user friends with $user_id?
     * @param int $user_id
     * @return UserFriend|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function isFriendsWith(int $user_id)
    {
        return UserFriend::where([
            ['user_id', '=', $this->getKey()],
            ['foreign_user_id', '=', $user_id],
        ])->orWhere([
            ['user_id', '=', $user_id],
            ['foreign_user_id', '=', $this->getKey()],
        ])
        ->first();
    }

    /**
     * Has this user sent a friend request to $user_id?
     * @param int $user_id
     * @return UserFriendRequest|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function hasReceivedFriendRequestFrom(int $user_id)
    {
        return UserFriendRequest::where([
            ['user_id', $user_id],
            ['added_user_id', $this->getKey()]
        ])->first();
    }

    /**
     * @param int $user_id
     * @return UserFriendRequest|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function hasSentFriendRequestTo(int $user_id)
    {
        return UserFriendRequest::where([
            ['user_id', $this->getKey()],
            ['added_user_id', $user_id]
        ])->first();
    }

    /**
     * Triggers the matching for a specific user
     */
    public function findMatches()
    {
        ProcessAllMatchings::dispatch(
            $this->id,
            false,
            true
        );
    }


    public function reasonOfDeath()
    {
        $matchingUserAnswer = $this->matchingAnswers()
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::REASON_OF_DEATH_QUESTION_ID);
            })
            ->with(
                [
                    'answer' => function ($q) {
                        $q->select('id', 'answer', 'code');
                    }
                ]
            )->first();

        if ($matchingUserAnswer !== null) {
            //TODO: get this info from DB
            $reason = DeadReasonHelper::get($matchingUserAnswer->answer->code);
            if ($reason !== null) {
                return $reason;
            } else {
                return $matchingUserAnswer->answer->answer;
            }
        }

        return '';
    }

    public function getMourningCode()
    {
        return $this->matchingAnswers()
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::MOURNING_REASON_QUESTION_ID);
            })
            ->with(
                [
                    'answer' => function ($q) {
                        $q->select('id', 'answer', 'code');
                    }
                ]
            )
            ->first()->answer->code ?? '';
    }

    public function getMourningForGender()
    {
        return $this->matchingAnswers()
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::MOURNING_FOR_QUESTION_ID);
            })
            ->with(
                [
                    'answer' => function ($q) {
                        $q->select('id', 'answer', 'code');
                    }
                ]
            )
            ->first()->answer->code ?? '';
    }

    public function getMourningForText()
    {
        $mourningForText = MournForHelper::get($this->mourning_code, $this->mourning_gender);
        if ($mourningForText) {
            return $mourningForText;
        }

        return $this->fetchFreeMourningForText();
    }

    private function fetchFreeMourningForText()
    {
        $answerId = $this->mourning_code === '1,05,11'
            ? self::MOURNING_FOR_FAMILY_MEMBER_FREETEXT_ID
            : self::MOURNING_FOR_ANOTHER_PERSON_FREETEXT_ID;

        return $this->matchingAnswers()->whereHas('answer', function ($q) use ($answerId) {
            $q->where('id', $answerId);
        })->first()->answer_text ?? '';
    }

    public function dateOfDeath()
    {
        $matchingUserAnswer = $this->matchingAnswers()
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::DATE_OF_DEATH_QUESTION_ID);
            })->first();

        if ($matchingUserAnswer !== null) {
            return $matchingUserAnswer->answer_text;
        }

        return '{"year":""}';
    }

    public function relationToDeceased()
    {
        $matchingUserAnswer = $this->matchingAnswers()
            ->whereHas('answer', function ($q) {
                $q->where('question_id', self::RELATION_TO_DECEASED_QUESTION_ID);
            })->with([
                'answer'=>function ($a) {
                    $a->select('id', 'answer', 'code');
                }
            ])
            ->first();

        if ($matchingUserAnswer !== null) {
            return $matchingUserAnswer->answer->answer;
        }

        return '';
    }

    public function getNewChatMessagesCount()
    {
        $blockUsersIds = self::getBlockUsersId();
        $userId =  $this->id;
        
        return ChatMessage
            ::leftJoin('chat_connections', 'chat_messages.chat_id', '=', 'chat_connections.id')
//            ->whereRaw('JSON_CONTAINS(chat_connections.participants,?)', [$userId])
            ->whereRaw("chat_connections.participants LIKE '%" . $userId . ",%' OR chat_connections.participants LIKE '%" . $userId . "]%'")
            ->where('chat_messages.user_id', '<>', $userId)
            ->whereNull('chat_messages.read_at')
            ->get()
            ->filter(function ($item) use ($blockUsersIds, $userId) {
                $participants = explode(',',substr($item->participants, 1, -1));
                $results = array_filter($participants, function ($id) use ($blockUsersIds, $userId){
                    return (int) $id !== $userId && !in_array($id, $blockUsersIds);
                });
                return !empty($results);
            })
            ->count();
    }

    public function getNewChatMessageCountByChat()
    {
        $blockUsersIds = self::getBlockUsersId();
        $userId =  $this->id;
        
        return ChatMessage
            ::leftJoin('chat_connections', 'chat_messages.chat_id', '=', 'chat_connections.id')
//            ->whereRaw('JSON_CONTAINS(chat_connections.participants,?)', [$userId])
            ->whereRaw("chat_connections.participants LIKE '%" . $userId . ",%' OR chat_connections.participants LIKE '%" . $userId . "]%'")
            ->where('chat_messages.user_id', '<>', $userId)
            ->whereNull('chat_messages.read_at')
            ->get()
            ->filter(function ($item) use ($blockUsersIds, $userId) {
                $participants = explode(',',substr($item->participants, 1, -1));
                $results = array_filter($participants, function ($id) use ($blockUsersIds, $userId){
                    return (int) $id !== $userId && !in_array($id, $blockUsersIds);
                });
                return !empty($results);
            })
            ->groupBy('user_id')
            ->map(function ($item) {
                return $item->count();
            });
    }

    public function getChatMessagesReceivedCount()
    {
        return ChatMessage::selectRaw('count(*) as total')
            ->join('chat_connections', 'chat_messages.chat_id', '=', 'chat_connections.id')
//            ->whereRaw('JSON_CONTAINS(chat_connections.participants,?)', [$this->id])
            ->whereRaw("chat_connections.participants LIKE '%" . $this->id . ",%' OR chat_connections.participants LIKE '%" . $this->id . "]%'")
            ->where('chat_messages.user_id', '<>', $this->id)
            ->first()
            ->total;
    }

    public function getChatsWithMessagesSendedCount()
    {
        return ChatConnection
                ::whereHas('messages', function($q){
                    $q->where('user_id','=', $this->id);
                })
//                ->whereRaw('JSON_CONTAINS(chat_connections.participants,?)', $this->id)
            ->whereRaw("chat_connections.participants LIKE '%" . $this->id . ",%' OR chat_connections.participants LIKE '%" . $this->id . "]%'")
                ->get()
                ->count();
    }

    public function getChats()
    {
        return ChatConnection
            ::with('messages')
//            ->whereRaw('JSON_CONTAINS(chat_connections.participants,?)', $this->id)
            ->whereRaw("chat_connections.participants LIKE '%" . $this->id . ",%' OR chat_connections.participants LIKE '%" . $this->id . "]%'")
            ->get();
    }

    public function getFriendStatus($foreign_user_id)
    {
        if ($this->isFriendsWith($foreign_user_id)) {
            return 2;
        } // they are friends
        if ($this->friendRequestSent($foreign_user_id)) {
            return 0;
        } // waiting to be friends
        if ($this->friendRequestReceived($foreign_user_id)) {
            return 1;
        } // has to accept friend request
        return 3; // rejected
    }

    private function friendRequestSent($foreign_user_id)
    {
        return DB::table('user_friend_requests')->where(
            [['user_id', $this->id],
                ['added_user_id', $foreign_user_id],]
        )->get()->first();
    }

    private function friendRequestReceived($foreign_user_id)
    {
        return DB::table('user_friend_requests')->where([['user_id', $foreign_user_id], ['added_user_id', $this->id]])
            ->get()->first();
    }

    public function delete() {
        $user = self::find($this->id);

        $sendinBlue = new SendinBlueHandler($user);
        $sendinBlue->emitUserDeleted();

        parent::delete();

    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        if(Cache::has('remote_login')) {
            Cache::forget('remote_login');
            return true;
        }
        return Hash::check($password, $this->password);
    }


    public function addHasSeen(User $user, $attribute)
    {
        $hasSeen = $user->has_seen;
        $hasSeen[] = strtolower($attribute);
        $user->has_seen = $hasSeen;
        $user->save();
    }

    public function removeHasSeen(User $user, $attribute)
    {
        $hasSeen =$user->has_seen;
        $key = array_search ($attribute, $hasSeen);
        unset($hasSeen[$key]);
        $user->has_seen = $hasSeen;
        $user->save();
    }

    public function getBlockUsersId()
    {
        return $this->blockListItems()->pluck('blocked_user_id')->all();
    }

    public function getIsBusinessAccountAttribute() {
        return $this->hasAnyRole('company','funeral-company') && $this->b2bUser()->exists();
    }

    public function  getRoleName() {
        return $this->getRoleNames()->filter(fn($item) => $item !== 'super-admin')->first();
    }
}
