<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebController extends Controller
{
    public function index(Request $request)
    {
        $this->manageReferrerHeaders($request);
        return view('layouts.vueapp');
    }

    public function getEnvVars(): JsonResponse
    {
        $response = [
            "MATOMO_TRACKING_ENABLED" => [
                "content" => config('matomo.tracking_enabled', true),
                "type" => "boolean"
            ],
            "MATOMO_TRACKING_SITE_ID" => [
                "content" => config('matomo.tracking_id', null),
                "type" => "number"
            ],
            "MATOMO_TRACKING_CROSS_SITE_LINKING" => [
                "content" => config('matomo.cross_site_linking_enabled', false),
                "type" => "boolean"
            ],
            "MATOMO_TRACKING_SITES" => [
                "content" => config('matomo.cross_site_linking_sites', null),
                "type" => "string"
            ],
            "TROSTHELDEN_URL" => [
                "content" => config('app.trosthelden_url', ''),
                "type" => "string"
            ],
            "PUSHER_APP_KEY" => [
                "content" => config('broadcasting.connections.pusher.key', ''),
                "type" => "string"
            ],
            "PUSHER_APP_CLUSTER" => [
                "content" => config('broadcasting.connections.pusher.options.cluster', ''),
                "type" => "string"
            ],
            "STORYBLOK_TOKEN" => [
                "content" => config('storyblok.token', ''),
                "type" => "string"
            ],
            "STORYBLOK_VERSION" => [
                "content" => config('storyblok.version', ''),
                "type" => "string"
            ],
            "STORYBLOK_DESTINATION" => [
                "content" => config('storyblok.destination', ''),
                "type" => "string"
            ],
            "BILLWERK_ROUTE_SUBSCRIPTION" => [
                "content" => config('billwerk.routes.subscription', ''),
                "type" => "string"
            ],
            "BILLWERK_PUBLIC_API_KEY" => [
                "content" => config('billwerk.auth.public_api_key', ''),
                "type" => "string"
            ]
        ];

        return response()->json($response);
    }

    protected function manageReferrerHeaders(Request $request) {
        $seconds = 1800;
        $referrer = $request->header('referrer');
        $referringDomain = $request->header('referring-domain');

        if($referrer) Cache::put('referrer', $referrer, $seconds);
        if($referringDomain) Cache::put('referring_domain', $referringDomain, $seconds);
    }
}
