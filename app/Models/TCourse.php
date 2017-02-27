<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TCourse extends Model
{
    protected $table = 'teacher_course';

    protected $primaryKey = 'tcid';

    protected $fillable = ['trid', 'scNum', 'jxbID', 'year','hash_day', 'hash_lesson', 'begin_lesson', 'day', 'lesson', 'course',
        'teacher', 'type', 'classroom', 'majorName', 'class', 'rawWeek', 'period', 'week'];

    public function getCourse($trid, $year)
    {
        $need = ['trid', 'scNum', 'jxbID', 'hash_day', 'hash_lesson', 'begin_lesson'
         , 'day', 'lesson', 'course', 'teacher', 'type', 'classroom', 'rawWeek', 'period'
        , 'week'];
        if (!$res = $this->where('trid', $trid)->where('year', $year)->select($need)->get()) {
            return false;
        }
        return $res;
    }
}
