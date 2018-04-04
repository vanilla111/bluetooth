<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SList extends Model
{
    protected $table = 'course_stu_list';

    protected $primaryKey = 'id';

    protected $fillable = ['jxbID', 'year','stuNum', 'name', 'gender', 'major', 'majorName', 'class', 'academy',
        'grade', 'type'];

    public function getStuListByJxbId($jxbId) {
        $need = ['stuNum', 'name', 'gender', 'major', 'majorName', 'class', 'academy',
        'grade', 'type'];
        return $this->select($need)->where('jxbID', $jxbId)->get();
    }

    public function getJxbStuNum($jxbId) {
        return $this->where('jxbID', $jxbId)->count();
    }
}
