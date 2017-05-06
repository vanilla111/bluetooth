<?php

namespace App\Models\Zhihu;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'zh_question';

    protected $primaryKey = 'id';

    protected $fillable = ['uid', 'answer_Count', 'recent', 'title', 'content', 'exciting', 'naive', 'type', 'author_name'];
}
