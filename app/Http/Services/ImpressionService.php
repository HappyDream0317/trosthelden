<?php

namespace App\Http\Services;

use App\Impression;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpressionService
{
    /**
     * @param $post_id
     * @return JsonResponse
     */
    public function countPostImpression($post_id)
    {
        $impressions = $this->countImpression('App\Post', $post_id);

        return response()->json([
            'success' => true,
            'impressions' => $impressions,
        ]);
    }

    /**
     * @param $comment_id
     * @return JsonResponse
     */
    public function countCommentImpression($comment_id)
    {
        $impressions = $this->countImpression('App\PostComment', $comment_id);

        return response()->json([
            'success' => true,
            'impressions' => $impressions,
        ]);
    }

    /**
     * @param $type
     * @param $id
     * @return mixed
     */
    protected function countImpression($type, $id)
    {
        $existing_impression = Impression::where('impressionable_type', $type)
            ->where('impressionable_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $existing_impression) {
            Impression::create([
                'user_id'       => Auth::id(),
                'impressionable_id'   => $id,
                'impressionable_type' => $type,
            ]);
        }
    }

    /**
     * @param $type
     * @param $id
     * @return mixed
     */
    public function getImpressions($type, $id)
    {
        return Impression::where('impressionable_type', $type)->where('impressionable_id', $id)->count();
    }
}
