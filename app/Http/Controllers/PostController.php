<?php

namespace App\Http\Controllers;

use App\Events\PostNew;
use App\Http\Resources\Posts as PostResource;
use App\Http\Services\ImpressionService;
use App\Post;
use App\PostComment;
use App\UserFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    const MOURNING_CODE_TO_GROUPS = [
        'for-all' => [42, 43],
        '1,01' => [16,17],
        '1,02' => [13],
        '1,03' => [21],
        '1,04' => [15],
        '1,05,01' => [22],
        '1,05,02' => [14],
        '1,06' => [18],
    ];

    private $pagination_per_page = 4;
    protected ImpressionService $impressionService;

    public function __construct()
    {
        $this->impressionService = new ImpressionService();
        $this->middleware(['auth:api']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|string',
            'message' => 'required|string',
        ]);
    }

    public function savePost(Request $request)
    {
        $this->validator($request->all())->validate();

        $post = new Post;
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->message = $request->message;
        $post->group_id = $request->group_id;

        if ($post->save()) {
            $updated_post = $post->fresh(['author', 'comments']);
            broadcast(new PostNew($updated_post));

            return response()->json($updated_post);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function getPost(Request $request, $post_id)
    {
        $this->impressionService->countPostImpression($post_id);

        return new PostResource(
            Post::with(['author', 'comments'])
                ->where('id', $post_id)
                ->withCount(['impressions', 'comments'])
                ->first()
        );
    }

    public function getPosts(Request $request, $group_id)
    {
        // TODO: Optimize?
        $posts = Post::where('group_id', $group_id)
            ->orderBy('created_at', 'desc')
            ->with(['author'])
            ->withCount(['impressions', 'comments'])
            ->paginate($this->pagination_per_page);

        return PostResource::collection($posts);
    }

    public function getLatestRepliesPerUser(Request $request)
    {
        $perPage = $request->get('perPage') ?? 3;

        $replies = PostComment::
            with([
                    'post',
                    'author',
                    'post.group',
                    'post.group.category'
                ])
            ->whereHas('post', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        return response()->json($replies);
    }

    public function getLatestFriendPosts(Request $request)
    {
        $perPage = $request->get('perPage') ?? 3;

        $friendIds = UserFriend::where('user_id', Auth::id())
            ->orWhere('foreign_user_id', Auth::id())
            ->get()
            ->map(function (UserFriend $friend) {
                return $friend->friend->id;
            });

        $posts = Post::whereIn('user_id', $friendIds)
            ->with(
                [
                    'author',
                    'group',
                    'group.category'
                ]
            )
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * Fetches posts related to the "mourning code"
     * which is set in the first question of the frabo.
     *
     * In short: the user should see posts in the dashboard
     * which are related to their mourning reason.
     *
     * This section also includes posts for all users.
     *
     * @param Request $request
     * @return mixed
     * @since 2021-01-07
     */

    public function getSimilarMourningPosts(Request $request)
    {
        $perPage = $request->get('perPage') ?? 3;
        $user = Auth::user();

        $mourningCode = $user->getMourningCode();

        if ($mourningCode !== '1,05,01' && $mourningCode !== '1,05,02') {
            $explodedCode = explode(',', $mourningCode);
            array_pop($explodedCode);
            $mourningCode = implode(',', $explodedCode);
        }

        $mourningGroupId = $mourningCode && isset(self::MOURNING_CODE_TO_GROUPS[$mourningCode])
            ? self::MOURNING_CODE_TO_GROUPS[$mourningCode]
            : [];

        return Post::whereIn('group_id', [
            ...self::MOURNING_CODE_TO_GROUPS['for-all'],
            ...$mourningGroupId
        ])
            ->with(
                [
                    'author',
                    'group',
                    'group.category'
                ]
            )
            ->whereNotIn('user_id', [$user->id])
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }
}
