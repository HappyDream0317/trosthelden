<?php

namespace App\Events;
 
use Illuminate\Queue\SerializesModels;

class RegisteredCompany
{
    use SerializesModels;
    
    /**
     * The regstered user.
     *
     * @var  \App\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\User $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
