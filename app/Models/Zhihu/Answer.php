<?php

namespace App\Models\Zhihu;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = 'zh_answer';

    protected $primaryKey = 'id';

    protected $fillable = ['uid', 'qid', 'content', 'exciting', 'naive', 'best', 'created_at'];
}
