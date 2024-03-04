<?php

namespace App\Mail;

use App\Post;
use App\PostComment;
use App\User;
use Illuminate\Mail\Mailable;

class ReportPostEmail extends Mailable
{
    /**
     * @var Post
     * @var User
     * @var User
     * @var string
     */
    private $post;
    private $reporter;
    private $reason;
    private $reportedUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $reporter, $reason)
    {
        $this->post = $post;
        $this->reporter = $reporter;
        $this->reason = $reason;
        $this->reportedUser = User::find($this->post->user_id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $title = __('Post melden');
        $this->subject($title);

        return $this->markdown('emails.report.post_report')
            ->with([
                'title' => $title,
                'post_url' => config('app.url').'/post/'.$this->post->id,
                'post_title' => $this->post->title,
                'reporter_id' => $this->reporter->id,
                'reporter_username' => $this->reporter->nickname,
                'reported_user_id' => $this->reportedUser->id,
                'reported_user_username' => $this->reportedUser->nickname,
                'reason' => $this->reason,
            ]);
    }
}
