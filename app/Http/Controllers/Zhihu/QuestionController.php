<?php

namespace App\Http\Controllers\Zhihu;

use App\Models\Zhihu\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function getQuestionList(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $count = $request->get('count') ? : 20;
        $user_info = $request->get('user');

    }

    public function question(Request $request)
    {
        $question_info = $request->only(['title', 'content', 'type']);
        $user_info = $request->get('user');

        $question_m = new Question();
        $data = [
            'uid' => $user_info['id'],
            'title' => $question_info['title'],
            'content' => $question_info['content'],
            'type' => $question_info['type']
        ];
        if(!$question_m->create($data))
            return response()->json([
                'status' => 500,
                'info' => 'failed'
            ], 500);

        return response()->json([
            'status' => 200,
            'info' => 'success'
        ], 200);
    }

    public function accept(Request $request)
    {
        //
    }
}
