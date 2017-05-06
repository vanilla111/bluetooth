<?php

namespace App\Http\Controllers\Zhihu;

use App\Models\Zhihu\Answer;
use App\Models\Zhihu\Question;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuestionController extends Controller
{
    public function getQuestionList(Request $request)
    {
        //$page = $request->get('page') ? : 1;
        $count = $request->get('count') ? : 20;
        $user_info = $request->get('user');
        $type = $request->get('type') ? : 'noType';
        $question_m = new Question();
        $data = $question_m->where('type', $type)->orderBy('created_at', 'desc')->paginate($count);
        return response()->json([
            'status' => 200,
            'info' => 'success',
            'data' => $data
        ], 200);
    }

    public function question(Request $request)
    {
        $question_info = $request->only(['title', 'content', 'type']);
        $user_info = $request->get('user');
        $type = $request->get('type') ? : 'noType';

        if (empty($question_info['title']) || empty($question_info['content']))
            return response()->json([
                'status' => 400,
                'info' => '标题正文不能为空'
            ], 400);

        $question_m = new Question();
        $data = [
            'uid' => $user_info['id'],
            'author_name' => $user_info['username'],
            'title' => $question_info['title'],
            'content' => $question_info['content'],
            'type' => $type
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
        $qid = $request->get('qid');
        $aid = $request->get('aid');
        $user = $request->get('user');

        if (empty($qid) || empty($aid))
            return response()->json([
                'status' => 400,
                'info' => 'qid, aid不能为空'
            ], 200);

        $questin_m = new Question();
        $answer_m = new Answer();
        $q_res = $questin_m->where('id', $qid)->select('uid')->first();
        $a_res = $answer_m->where('id', $aid)->select('qid')->first();
        if ($q_res['uid'] != $user['id'] || $a_res['qid'] != $qid)
            return response()->json([
                'status' => 403,
                'info' => '拒绝服务'
            ], 403);

        $condition = [
            'qid' => $qid,
            'best' => 1,
        ];
        $res = $answer_m->where($condition)->update(['best' => 0]);
        if (!$res)
            return response()->json([
                'status' => 500,
                'info' => '未知错误'
            ], 500);

        $data = [
            'best' => 1,
        ];

        $answer_m->where('id', $aid)->update($data);

        return response()->json([
            'status' => 200,
            'info' => 'success'
        ], 200);
    }
}
