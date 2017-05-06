<?php

namespace App\Http\Controllers\Zhihu;

use App\Models\Zhihu\Question;
use Illuminate\Http\Request;

use App\Models\Zhihu\Favorite;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    public function favorite(Request $request)
    {
        $user = $request->get('user');
        $qid = $request->get('qid');
        $favorite_m = new Favorite();
        if (empty($qid))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空'
            ], 400);
        $question_m = new Question();
        $res = $question_m->where('id', $qid)->select('title')->first();
        $data = [
            'uid' => $user['id'],
            'qid' => $qid,
            'title' => $res['title'],
            'author' => $user['username']
        ];
        if (!$favorite_m->create($data))
            return response()->json([
                'status' => 400,
                'info' => 'failed'
            ], 400);


        return response()->json([
            'status' => 200,
            'info' => 'success'
        ], 200);
    }

    public function cancelFavorite(Request $request)
    {
        $user = $request->get('user');
        $qid = $request->get('qid');
        $favorite_m = new Favorite();
        if (empty($qid))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空'
            ], 400);

        if (!$favorite_m->where('qid', $qid)->delete())
            return response()->json([
                'status' => 400,
                'info' => 'failed'
            ], 400);


        return response()->json([
            'status' => 200,
            'info' => 'success'
        ], 200);
    }

    public function getFavoriteList(Request $request)
    {
        $page = $request->get('page') ? : 1;
        $user = $request->get('user');
        $qid = $request->get('qid');
        $count = $request->get('count') ? : 20;

        if (empty($qid))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空'
            ], 400);

        $favorite_m = new Favorite();

        $condition = [
            'uid' => $user['id'],
            'qid' => $qid
        ];
        $data = $favorite_m->where($condition)
            ->orderBy('created_at', 'desc')
            ->paginate($count);

        return response()->json([
            'status' => 200,
            'info' => 'success',
            'data' => $data
        ], 200);
    }
}
