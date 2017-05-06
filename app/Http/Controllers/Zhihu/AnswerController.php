<?php

namespace App\Http\Controllers\Zhihu;

use Illuminate\Http\Request;

use App\Models\Zhihu\Question;
use App\Models\Zhihu\Answer;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnswerController extends Controller
{
    public function getAnswerList(Request $request)
    {
        //$page = $request->get('page') ? : 1;
        $count = $request->get('count') ? : 20;
        $user_info = $request->get('user');
        $qid = $request->get('qid');
        if (empty($qid))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空',
            ], 400);
        $answer_m = new Answer();
        $data = $answer_m->where('qid', $qid)
            ->orderBy('best', 'desc')
            ->orderBy('exciting', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($count);

        return response()->json([
            'status' => 200,
            'info' => 'success',
            'data' => $data
        ], 200);
    }

    public function answer(Request $request)
    {
        $answer_info = $request->only(['qid', 'content']);
        $user_info = $request->get('user');

        if (empty($answer_info['qid']))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空'
            ], 400);

        $answer_m = new Answer();
        $data = [
            'uid' => $user_info['id'],
            'qid' => $answer_info['qid'],
            'content' => $answer_info['content']
        ];
        if(!$answer_m->create($data))
            return response()->json([
                'status' => 500,
                'info' => 'failed'
            ], 500);

        return response()->json([
            'status' => 200,
            'info' => 'success'
        ], 200);
    }
}
