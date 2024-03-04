<?php

namespace App\Http\Controllers;

use App\ProfileQuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileQuestionAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getAnswerFromUserForQuestion($profile_question_id)
    {
        return ProfileQuestionAnswer::where('user_id', Auth::id())->where('profile_question_id', $profile_question_id);
    }

    public function getAllAnswersFromUser()
    {
        return ProfileQuestionAnswer::where('user_id', Auth::id());
    }

    public function getAllAnswersFromSpecificUser($user_id)
    {
        return ProfileQuestionAnswer::where('user_id', $user_id);
    }

    public function saveAnswer(Request $request, $profile_question_id)
    {
        if (!$request->exists('answer_text')) {
            return response()->json([
                'success' => false,
            ]);
        }

        $answer = ProfileQuestionAnswer::updateOrCreate([
            'user_id' => Auth::id(),
            'profile_question_id' => $profile_question_id,
        ], [
            'answer_text' => $request->get('answer_text') ?? "",
        ]);

        if ($answer) {
            return response()->json([
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
            ]);
        }
    }
}
