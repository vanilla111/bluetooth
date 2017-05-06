<?php

namespace App\Models\Zhihu;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'zh_favorite';

    protected $primaryKey = 'id';

    protected $fillable = ['uid', 'qid'];
}
