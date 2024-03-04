<?php

namespace App\Http\Controllers;

use App\Exports\StillOpenFriendRequests;
use App\Exports\UserClusterRankingExport;
use App\Exports\UserExport;
use App\Exports\UserAnswerExport;
use App\Exports\UserExportFraboStatus;
use App\Exports\UserInsightsExport;
use App\Exports\UserMessageExport;
use App\Exports\UserNotificationExport;
use App\Exports\UserStatusReferrerExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function users()
    {
        return Excel::download(new UserExport, 'users.xlsx');
    }

    public function usersFraboStatus()
    {
        return Excel::download(new UserExportFraboStatus, 'users-frabo-status.xlsx');
    }

    public function userAnswers()
    {
        return Excel::download(new UserAnswerExport, 'user-answers.xlsx');
    }

    public function userMessages()
    {
        return Excel::download(new UserMessageExport, 'user-messages.xlsx');
    }

    public function userInsights()
    {
        return Excel::download(new UserInsightsExport, 'user-insights.xlsx');
    }

    public function userNotifications()
    {
        return Excel::download(new UserNotificationExport, 'user-notifications.xlsx');
    }

    public function userClusterRanking()
    {
        return Excel::download(new UserClusterRankingExport, 'user-cluster-ranking.xlsx');
    }

    public function stillOpenFriendRequests()
    {
        return Excel::download(new StillOpenFriendRequests, 'still-open-friend-requests.xlsx');
    }

    public function  userStatusReferrer()
    {
        return Excel::download(new UserStatusReferrerExport, 'user-status-referrer.xlsx');
    }
}
