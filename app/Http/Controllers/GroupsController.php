<?php

namespace App\Http\Controllers;

use App\Group;
use App\GroupCategory;
use App\http\Resources\GroupCategories as GroupCategoryResource;
use App\Http\Resources\Groups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GroupsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */   public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getAllGroups()
    {
        $return = GroupCategory::with(['groups'])->get();

        return $return;
    }

    public function getGroup(Request $request, $group_id)
    {
        return new Groups(Group::with(['category'])->where('id', $group_id)->first());
    }
}
