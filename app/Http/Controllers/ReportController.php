<?php
    
    
    namespace App\Http\Controllers;

    use App\Mail\ReportCommentEmail;
    use App\Mail\ReportPostEmail;
    use App\Mail\ReportProfileEmail;
    use App\Post;
    use App\PostComment;
    use App\Report;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    
    class ReportController extends Controller
    {
        public function __construct()
        {
            $this->middleware(['auth:api']);
        }
        
        public function reportPost(int $post_id, Request $request)
        {
            $reason = $request->json('reason') ?? null;
            $this->handleReport('App\Post', $post_id, $reason);
            
            $post = Post::find($post_id);
            $reporter = User::find(Auth::id());
            
            Mail::to(config('app.trosthelden_report_email'))
                ->send(new ReportPostEmail($post, $reporter, $reason));
            
            return response()->json(
                [
                    'success' => true,
                ]
            );
        }
        
        public function reportPostComment(int $comment_id, Request $request)
        {
            $reason = $request->json('reason') ?? null;
            $this->handleReport('App\PostComment', $comment_id, $reason);
            
            $postComment = PostComment::find($comment_id);
            $reporter = User::find(Auth::id());
            
            Mail::to(config('app.trosthelden_report_email'))
                ->send(new ReportCommentEmail($postComment, $reporter, $reason));
            
            return response()->json(
                [
                    'success' => true,
                ]
            );
        }
        
        public function reportProfile(int $user_id, Request $request)
        {
            $reason = $request->json('reason') ?? null;
            $this->handleReport('App\User', $user_id, $reason);
            
            $user = User::find($user_id);
            $reporter = User::find(Auth::id());
            
            Mail::to(config('app.trosthelden_report_email'))
                ->send(new ReportProfileEmail($user, $reporter, $reason));
            
            return response()->json(
                [
                    'success' => true,
                ]
            );
        }
        
        public function handleReport($type, $id, $reason = null)
        {
            $existing_report = Report::where('reportable_type', $type)->where(
                'reportable_id',
                $id
            )->where('user_id', Auth::id())->first();
            
            if (!$existing_report) {
                Report::create(
                    [
                        'user_id'         => Auth::id(),
                        'reportable_id'   => $id,
                        'reportable_type' => $type,
                        'reason'          => $reason,
                    ]
                );
            }
            
            return null;
        }
    }
