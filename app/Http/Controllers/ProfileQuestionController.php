<?php

namespace App\Http\Controllers;

use App\ProfileQuestion;

class ProfileQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getProfileQuestions()
    {
        return ProfileQuestion::where('active', '=', true)->get()->keys()->sortBy('position');
    }
}
