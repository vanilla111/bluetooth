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

        if (empty($qid))
            return response()->json([
                'status' => 400,
                'info' => 'qid不能为空'
            ], 400);

        $favorite_m = new Favorite();
        $question_m = new Question();

        $is_favorite = $favorite_m->where(['qid' => $qid, 'uid' => $user['id']])->first();

        if ($is_favorite)
            return response()->json([
                'status' => '200',
                'info' => '已经收藏'
            ], 200);

        $res = $question_m->where('id', $qid)->select('title')->first();

        if (!$res)
            return response()->json([
                'status' => 400,
                'info' => 'qid未找到'
            ], 400);

        $data = [
            'uid' => $user['id'],
            'qid' => $qid,
            'title' => $res['title'],
            'author_name' => $user['username']
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
        $count = $request->get('count') ? : 20;

        $favorite_m = new Favorite();

        $condition = [
            'uid' => $user['id'],
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
