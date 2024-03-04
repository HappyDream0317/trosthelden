<?php

namespace App\Http\Controllers;

use App\Events\CommentNew;
use App\Http\Resources\PostComment as PostCommentResource;
use App\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'comment' => 'required',
        ]);
    }

    public function getComments(Request $request, $post_id)
    {
        $comments = PostComment::where('post_id', $post_id)
            ->whereNull('parent_comment_id')
            //go 2 levels deep with comments
            ->with([
                'author',
                'comments',
                'comments.author',
                'comments.comments',
                'comments.comments.author'])
            ->get();

        return PostCommentResource::collection($comments);
    }

    /**
     * @param Request $request
     * @param $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveComment(Request $request, $post_id)
    {
        $this->validator($request->all())->validate();
        $comment = new PostComment;
        $comment->user_id = Auth::id();
        $comment->post_id = $post_id;
        $comment->comment = Purifier::clean($request->comment);
        $comment->parent_comment_id = (int) $request->comment_parent === (int) $post_id ? null : $request->comment_parent;

        if ($comment->save()) {
            $updated_comment = $comment->fresh(['author']);
            broadcast(new CommentNew($updated_comment));

            return response()->json($updated_comment);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PostComment  $postMessage
     * @return \Illuminate\Http\Response
     */
    public function show(PostComment $postMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PostComment  $postMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(PostComment $postMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PostComment  $postMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostComment $postMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PostComment  $postMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostComment $postMessage)
    {
        //
    }
}
