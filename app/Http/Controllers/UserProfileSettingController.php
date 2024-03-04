<?php

namespace App\Http\Controllers;

use App\User;
use App\UserProfileSetting;

class UserProfileSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getVisibilitySettings()
    {
        return UserProfileSetting::where('user_id', Auth::id())->value('visibility_info_job', 'visibility_info_religion', 'visibility_info_children');
    }

    public function postSetVisibilitySettings($element)
    {
        UserProfileSetting::where('user_id', Auth::id())->update(['visibility_info_'.$element =>true]);
    }

    public function postUnsetVisibilitySettings($element)
    {
        UserProfileSetting::where('user_id', Auth::id())->update(['visibility_info_'.$element =>false]);
    }
}
