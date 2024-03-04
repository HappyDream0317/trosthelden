<?php

namespace App\Http\Controllers;

use App\User;
use App\Referrer;
use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ReferrerController extends Controller
{
    /**
     * Store a new referrer.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();
        try {
            $data = $this->builNewReferrerData($request, $user);
            $referrer = new Referrer($data);
            $referrer->save();
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    }

    public function builNewReferrerData(Request $request, User $user): array
    {
        $referrer = $request->get('referrer');
        if(Cache::has('referrer')) {
            $referrer = $referrer ?? Cache::get('referrer');
            Cache::forget('referrer');
        }

        $referring_domain = $request->get('referring_domain');
        if(Cache::has('referring_domain')) {
            $referring_domain = $referring_domain ?? Cache::get('referring_domain');
            Cache::forget('referring_domain');
        }

        $user_id = $user->id;
        $content = $request->get('content');
        $campaign = $request->get('campaign');
        $medium = $request->get('medium');
        $source = $request->get('source');
        $keyword = $request->get('keyword');

        return compact(
            'user_id',
            'referrer',
            'referring_domain',
            'content',
            'campaign',
            'medium',
            'source',
            'keyword'
        );
    }

    public function checkHeaders (Request $request)
    {

        if(Cache::has('referrer') || Cache::has('referring_domain')) {
            return response()->json([
                'exist' => true
            ], Response::HTTP_OK);
        }
        return response()->json([
            'exist' => false
        ], Response::HTTP_OK);
    }
}
