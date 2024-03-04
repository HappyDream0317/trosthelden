<?php

namespace App\Http\Controllers;

use App\FaqQuestion;
use Illuminate\Support\Facades\Auth;

class FaqQuestionController extends Controller
{
    public function getFAQ()
    {
        $return = [];
        $questions = FaqQuestion::all();
        foreach ($questions as $question) {
            $return[$question->topic][] = ['id'=>$question->id, 'question'=>$question->question, 'answer'=>$question->answer];
        }
        return response()->json($return);
    }
}
