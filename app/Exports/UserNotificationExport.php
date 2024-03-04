<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserNotificationExport implements FromCollection
{
    public function collection()
    {
        $userList = collect([]);
        $userList->add(['E-Mail', 'Nickname', 'Role', 'Last Seen At', 'Promotions', 'Surveys', 'Updates', 'Matches', 'Messages', 'Friend Requests', 'Friend Request Confirmations', 'Own Post Replied', 'Own Comment Replied', 'Friend Post Actions']);

        $users = User::get()->filter(function ($value, $key) {
            return $value->matching_step == -1 &&
                !$value->has_nova_access &&
                !strpos($value->email, '@yesdevs.com') &&
                !strpos($value->email, '@trosthelden.de');
        });

//        $users = User::get();

        if ($users) {
            foreach ($users as $user) {

//                if($user->id !== 60) continue;

                $lastSeenAt = null;

                if ($user->last_seen_at) {
                    $date = new \DateTime($user->last_seen_at);
                    $lastSeenAt = $date->format('d.m.Y');
                }

                $notifications = $user->notification_settings;

                if (!$notifications) {
                    continue;
                }

                $sendPromotions = $notifications['promotions'] ?? false;
                $sendSurveys = $notifications['surveys'] ?? false;
                $sendUpdates = $notifications['updates'] ?? false;

                $sendMatches = $notifications['matches'] ?? false;
                $sendChatMessages = $notifications['chat_message'] ?? false;
                $sendFriendRequests = $notifications['friend_request'] ?? false;
                $sendFriendRequestConfirmations = $notifications['friend_request_confirmation'] ?? false;
                $sendOwnPostReplied = $notifications['my_post_commented'] ?? false;
                $sendOwnCommentReplied = $notifications['my_comment_replied'] ?? false;
                $sendFriendPostsActions = $notifications['friend_post'] ?? false;

                $row = [
                    $user->email,
                    $user->nickname,
                    $user->getRoleName(),
                    $lastSeenAt,
                    $sendPromotions,
                    $sendSurveys,
                    $sendUpdates,
                    $sendMatches,
                    $sendChatMessages,
                    $sendFriendRequests,
                    $sendFriendRequestConfirmations,
                    $sendOwnPostReplied,
                    $sendOwnCommentReplied,
                    $sendFriendPostsActions,
                ];

                $userList->add($row);
            }
        }

        return $userList;
    }
}
