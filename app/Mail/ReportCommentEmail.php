<?php

namespace App\Mail;

use App\Post;
use App\PostComment;
use App\User;
use Illuminate\Mail\Mailable;

class ReportCommentEmail extends Mailable
{
    /**
     * @var PostComment
     * @var User
     * @var User
     * @var string
     */
    private $comment;
    private $reporter;
    private $reason;
    private $reportedUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PostComment $comment, User $reporter, $reason)
    {
        $this->comment = $comment;
        $this->reporter = $reporter;
        $this->reason = $reason;
        $this->reportedUser = User::find($this->comment->user_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Kommentar melden');
        $this->subject($title);

        return $this->markdown('emails.report.post_report')
            ->with([
                'title' => $title,
                'post_url' => config('app.url').'/post/'.$this->comment->post->id,
                'post_title' => $this->comment->post->title,
                'comment' => $this->comment->comment,
                'reporter_id' => $this->reporter->id,
                'reporter_username' => $this->reporter->nickname,
                'reported_user_id' => $this->reportedUser->id,
                'reported_user_username' => $this->reportedUser->nickname,
                'reason' => $this->reason,
            ]);
    }
}
