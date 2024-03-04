<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary(Request $request)
    {
        $groups = Auth::user()->groups()->with(['posts' => function ($q) {
            $q->latest();
            $q->take(5);
            $q->with(['author']);
            $q->withCount('impressions');
        }])->with(['category'])->get();

        return response()->json($groups);
    }
}
