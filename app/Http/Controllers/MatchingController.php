<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAllMatchings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MatchingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $matchingOrJob = new ProcessAllMatchings(
            $request->get("uid", null),
            $request->get("debug", false),
            $request->get("write", false),
        );

        if ($request->filled("uid")) {
            return response()->json([
              'html' => $matchingOrJob->calcMatching(),
              'params' => [
                  'debug' => $request->get("debug", false),
                  'write' => $request->get("write", false),
                  'userId' => $request->get("uid")
              ]
          ]);
        }

        if (Storage::exists('generating_matches.lock')) {
            return response()->json([
               'status' => "Job is already running."
            ]);
        }
        ProcessAllMatchings::dispatchAfterResponse(
            $request->get("uid"),
            $request->get("debug"),
            $request->get("write")
        );

        return response()->json(['status' => 'job dispatched']);
    }

    public function getCurrent(Request $request)
    {
        return Storage::download('latest_matches.html');
    }

    public function getCurrentStatus(Request $request)
    {
        $jobIsRunning = Storage::exists('generating_matches.lock');

        if ($jobIsRunning) {
            return response()->json(['status' => 'processing']);
        }
    }
}
