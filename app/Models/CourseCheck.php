<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseCheck extends Model
{
    protected $table = 'course_check';

    protected $primaryKey = 'ccid';

    protected $fillable = ['stuNum', 'stuName', 'trid','jxbID', 'course', 'year', 'month','week', 'hash_day', 'hash_lesson',
        'major', 'grade', 'class', 'scNum','status'];

    public function stuAttendance($user, $info)
    {
        $need = ['week', 'hash_day', 'hash_lesson', 'status', 'jxbID', 'course'];
        $condition = [
                'stuNum' => $user['stu_code'],
                'year' => $info['year'],
                'hash_day' => $info['hash_day'],
                'hash_lesson' => $info['hash_lesson'],
                'week' => $info['week']
            ];

        if ($info['week'] == 0)
            unset($condition['week']);

        if ($info['hash_day'] == 0)
            unset($condition['hash_day']);

        if ($info['hash_lesson'] == 0)
            unset($condition['hash_lesson']);

        if (!$res = $this->where($condition)->select($need)->get())
            return false;

        return $res;
    }

    public function teaAttendance($info)
    {
        $need = ['stuNum', 'stuName', 'week', 'hash_day', 'hash_lesson', 'status'];
        $condition = [
            'jxbID' => $info['jxbID'],
            'year' => $info['year'],
            'hash_day' => $info['hash_day'],
            'hash_lesson' => $info['hash_lesson'],
            'week' => $info['week']
        ];

        if ($info['week'] == 0)
            unset($condition['week']);

        if ($info['hash_day'] == 0)
            unset($condition['hash_day']);

        if ($info['hash_lesson'] == 0)
            unset($condition['hash_lesson']);

        if (!$res = $this->where($condition)->select($need)->get())
            return false;

        return $res;
    }

    public function getWeekStatistics($info, $trid)
    {
        $need = ['hash_day', 'status'];
        $condition = [
            'year' => $info['year'],
            'trid' => $trid,
            'major' => $info['major'],
            'grade' => $info['grade'],
            'scNum' => $info['scNum']
        ];

        if ($info['major'] == 0)
            unset($condition['major']);

        if ($info['grade'] == 0)
            unset($condition['grade']);

        if ($info['scNum'] == 0)
            unset($condition['scNum']);

        if (!$res = $this->where($condition)->select($need)->get())
            return false;

        for ($i = 0; $i < 7; $i++) {
            $count_leave[$i] = 0;
            $count_late[$i] = 0;
            $count_absence[$i] = 0;
        }

        foreach ($res as $key => $value) {
            switch ($value['status']) {
                case env('LEAVE') : {
                    $count_leave[$value['hash_day']]++;
                    break;
                }
                case env('LATE') : {
                    $count_late[$value['hash_day']]++;
                    break;
                }
                case env('ABSENCE') : {
                    $count_absence[$value['hash_day']]++;
                    break;
                }
            }
        }

        $data = [
            'leave' => $count_leave,
            'late' => $count_late,
            'absence' => $count_absence
        ];

        return $data;
    }

    public function getMonthStatistics($month, $trid)
    {
        $year = getCourseYear();
        $condition = [
            'trid' => $trid,
            'year' => $year,
            'month' => $month
        ];
        $need = ['status', 'created_at'];
        if (!$res = $this->where($condition)->select($need)->get())
            return false;

        $month_day = date('t', time());
        for ($i = 0; $i < $month_day; $i++) {
            $count[$i] = 0;
        }

        foreach ($res as $key => $value) {
            $day = intval(substr($value['created_at'], 8, 2));
            if ($value['status'] == env('ABSENCE'))
                $count[$day]++;
        }

        return $count;
    }

    public function getStuList($info, $trid)
    {
        $need = ['ccid', 'stuNum', 'stuName', 'hash_day','class', 'created_at'];
        $condition = [
            'trid' => $trid,
            'year' => $info['year'],
            'month' => $info['month'],
            'week' => $info['week'],
            'major' => $info['major'],
            'grade' => $info['grade'],
            'scNum' => $info['scNum'],
            'status' => $info['status']
        ];

        //返回当月信息,否则返回当周信息
        if ($info['this_month'] && !$info['today']) {
            $condition['month'] = getNowMonth();
            unset($condition['week']);
        }

        foreach ($condition as $key => $value)
            if (!$value && $condition[$key] != '0')
                unset($condition[$key]);

        if (empty($info['stuName']) && empty($info['stuNum'])) {
            if (!$res = $this->where($condition)->select($need)->paginate($info['per_page']))
                return false;
        } elseif (!empty($info['stuName'])) {
            if (!$res = $this->where($condition)
                ->where('stuName', 'like', '%' . $info['stuName'] . '%')
                ->select($need)
                ->paginate($info['per_page']))
                return false;
        } elseif (!empty($info['stuNum'])) {
            if (!$res = $this->where($condition)
                ->where('stuNum', 'like', '%' . $info['stuNum'] . '%')
                ->select($need)
                ->paginate($info['per_page']))
                return false;
        }

        if (!$info['today']) {
            return $res;
        }

        //返回当天信息
        $hash_day = date('N', time()) - 1;
        foreach ($res as $key => $value) {
            if ($value['hash_day'] != $hash_day) {
                unset($res[$key]);
            }
        }

        return $res;
    }
}
