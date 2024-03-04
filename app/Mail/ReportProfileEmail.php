<?php
    
    namespace App\Mail;

    use App\Post;
    use App\PostComment;
    use App\User;
    use Illuminate\Mail\Mailable;
    
    class ReportProfileEmail extends Mailable
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
        public function __construct(User $reportedUser, User $reporter, $reason)
        {
            $this->reporter = $reporter;
            $this->reason = $reason;
            $this->reportedUser = $reportedUser;
        }
        
        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            $title = __('Profil melden');
            $this->subject($title);
            
            return $this->markdown('emails.report.profile_report')
                ->with([
                           'title' => 'Profil gemeldet:',
                           'profile_url' => '' . config('app.url').'/profile/'.$this->reportedUser . '',
                           'reporter_id' => $this->reporter->id,
                           'reporter_username' => $this->reporter->nickname,
                           'reported_user_id' => $this->reportedUser->id,
                           'reported_user_username' => $this->reportedUser->nickname,
                           'reason' => $this->reason,
                       ]);
        }
    }
