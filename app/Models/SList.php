<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SList extends Model
{
    protected $table = 'course_stu_list';

    protected $primaryKey = 'id';

    protected $fillable = ['jxbID', 'year','stu_list'];
}
