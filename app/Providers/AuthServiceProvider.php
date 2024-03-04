<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\ActionEvent;
use App\B2BCode;
use App\B2BCoupon;
use App\B2BDiscount;
use App\B2BPartner;
use App\B2BPartnerRedirect;
use App\B2BUser;
use App\Billwerk;
use App\BillwerkCoupon;
use App\ChatConnection;
use App\ChatMessage;
use App\FaqQuestion;
use App\Group;
use App\GroupCategory;
use App\GroupUser;
use App\Impression;
use App\MatchingAnswer;
use App\MatchingAnswerType;
use App\MatchingAnswerValidation;
use App\MatchingClusterDimensionsDefinition;
use App\MatchingClusterRankingDefinition;
use App\MatchingQuestion;
use App\MatchingQuestionAnswerCondition;
use App\MatchingQuestionStepContent;
use App\MatchingUserAnswer;
use App\MatchingUserPartnerRanking;
use App\Post;
use App\PostComment;
use App\ProfileMessageAlert;
use App\ProfileMotto;
use App\ProfileProgress;
use App\ProfileQuestion;
use App\ProfileQuestionAnswer;
use App\ProfileVisibility;
use App\Referrer;
use App\Report;
use App\Tooltip;
use App\User;
use App\UserBlockListItem;
use App\UserFriend;
use App\UserFriendRequest;
use App\UserProfileSetting;
use App\UserWatchListItem;
use App\Policies\ActionEventPolicy;
use App\Policies\B2BCodePolicy;
use App\Policies\B2BCouponPolicy;
use App\Policies\B2BDiscountPolicy;
use App\Policies\B2BPartnerPolicy;
use App\Policies\B2BPartnerRedirectPolicy;
use App\Policies\B2BUserPolicy;
use App\Policies\BillwerkPolicy;
use App\Policies\BillwerkCouponPolicy;
use App\Policies\ChatConnectionPolicy;
use App\Policies\ChatMessagePolicy;
use App\Policies\FaqQuestionPolicy;
use App\Policies\GroupPolicy;
use App\Policies\GroupCategoryPolicy;
use App\Policies\GroupUserPolicy;
use App\Policies\ImpressionPolicy;
use App\Policies\MatchingAnswerPolicy;
use App\Policies\MatchingAnswerTypePolicy;
use App\Policies\MatchingAnswerValidationPolicy;
use App\Policies\MatchingClusterDimensionsDefinitionPolicy;
use App\Policies\MatchingClusterRankingDefinitionPolicy;
use App\Policies\MatchingQuestionPolicy;
use App\Policies\MatchingQuestionAnswerConditionPolicy;
use App\Policies\MatchingQuestionStepContentPolicy;
use App\Policies\MatchingUserAnswerPolicy;
use App\Policies\MatchingUserPartnerRankingPolicy;
use App\Policies\PostPolicy;
use App\Policies\PostCommentPolicy;
use App\Policies\ProfileMessageAlertPolicy;
use App\Policies\ProfileMottoPolicy;
use App\Policies\ProfileProgressPolicy;
use App\Policies\ProfileQuestionPolicy;
use App\Policies\ProfileQuestionAnswerPolicy;
use App\Policies\ProfileVisibilityPolicy;
use App\Policies\ReferrerPolicy;
use App\Policies\ReportPolicy;
use App\Policies\TooltipPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserBlockListItemPolicy;
use App\Policies\UserFriendPolicy;
use App\Policies\UserFriendRequestPolicy;
use App\Policies\UserProfileSettingPolicy;
use App\Policies\UserWatchListItemPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ActionEvent::class => ActionEventPolicy::class,
        B2BCode::class => B2BCodePolicy::class,
        B2BCoupon::class => B2BCouponPolicy::class,
        B2BDiscount::class => B2BDiscountPolicy::class,
        B2BPartner::class => B2BPartnerPolicy::class,
        B2BPartnerRedirect::class => B2BPartnerRedirectPolicy::class,
        B2BUser::class => B2BUserPolicy::class,
        Billwerk::class => BillwerkPolicy::class,
        BillwerkCoupon::class => BillwerkCouponPolicy::class,
        ChatConnection::class => ChatConnectionPolicy::class,
        ChatMessage::class => ChatMessagePolicy::class,
        FaqQuestion::class => FaqQuestionPolicy::class,
        Group::class => GroupPolicy::class,
        GroupCategory::class => GroupCategoryPolicy::class,
        GroupUser::class => GroupUserPolicy::class,
        Impression::class => ImpressionPolicy::class,
        MatchingAnswer::class => MatchingAnswerPolicy::class,
        MatchingAnswerType::class => MatchingAnswerTypePolicy::class,
        MatchingAnswerValidation::class => MatchingAnswerValidationPolicy::class,
        MatchingClusterDimensionsDefinition::class => MatchingClusterDimensionsDefinitionPolicy::class,
        MatchingClusterRankingDefinition::class => MatchingClusterRankingDefinitionPolicy::class,
        MatchingQuestion::class => MatchingQuestionPolicy::class,
        MatchingQuestionAnswerCondition::class => MatchingQuestionAnswerConditionPolicy::class,
        MatchingQuestionStepContent::class => MatchingQuestionStepContentPolicy::class,
        MatchingUserAnswer::class => MatchingUserAnswerPolicy::class,
        MatchingUserPartnerRanking::class => MatchingUserPartnerRankingPolicy::class,
        Post::class => PostPolicy::class,
        PostComment::class => PostCommentPolicy::class,
        ProfileMessageAlert::class => ProfileMessageAlertPolicy::class,
        ProfileMotto::class => ProfileMottoPolicy::class,
        ProfileProgress::class => ProfileProgressPolicy::class,
        ProfileQuestion::class => ProfileQuestionPolicy::class,
        ProfileQuestionAnswer::class => ProfileQuestionAnswerPolicy::class,
        ProfileVisibility::class => ProfileVisibilityPolicy::class,
        Referrer::class => ReferrerPolicy::class,
        Report::class => ReportPolicy::class,
        Tooltip::class => TooltipPolicy::class,
        User::class => UserPolicy::class,
        UserBlockListItem::class => UserBlockListItemPolicy::class,
        UserFriend::class => UserFriendPolicy::class,
        UserFriendRequest::class => UserFriendRequestPolicy::class,
        UserProfileSetting::class => UserProfileSettingPolicy::class,
        UserWatchListItem::class => UserWatchListItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
