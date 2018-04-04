<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Teacher;
use App\Models\TCourse;
use App\Models\SList;
use App\Models\CourseCheck;
use App\Jobs\checkAttendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $guard = 'teachers';

    public function toLogin(Request $request)
    {
        return view('login');
    }

    public function index(Request $request)
    {
        return view('index');
    }

    public function test(Request $request) {
        $arr = [];
        for ($i = 0; $i < 49; $i++) {
            $arr[$i] = rand(0, 5);
        }

        return implode(",", $arr);
    }

    /*登录*/
    public function login(Request $request)
    {
        $credentials = $request->only('trid','password');

        if (empty($credentials['trid']) || empty($credentials['password']))
            return response()->json([
                'status' => 403,
                'message' => '教师ID与密码不能为空',
                'data' => NULL
            ], 403);

        if ( $token = Auth::guard($this->getGuard())->attempt($credentials)) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                'message' => 'failed',
                'data' => NULL
            ], 403);
        }

    }

    public function getCourse(Request $request)
    {
        $year = getCourseYear();
        $user = $request->get('user');
        $week = $request->get('week') ? : 0;

        $course_m = new TCourse();
        if (! $courseList = $course_m->getCourse($user['trid'], $year)) {
            return response()->json([
                'status' => 403,
                'message' => 'failed',
            ], 403);
        }

        if ($week < 0) {
            return response()->json([
                'status' => 403,
                'message' => 'week须为非负整数' 
                ], 403);
        }

        //返回指定周数的课表
        if ($week != 0) {
            foreach ($courseList as $key => $value) {
                $weeks = explode(',', $value['week']);
                $temp = [];
                if (!in_array($week, $weeks))
                    unset($courseList[$key]);
                else {
                    for ($i = 0; $i < count($weeks) ; $i++) { 
                         array_push($temp, intval($weeks[$i]));
                     } 
                }
                $value['week'] = $temp;
                $value['hash_day'] = intval($value['hash_day']);
                $value['hash_lesson'] = intval($value['hash_lesson']);
                $value['begin_lesson'] = intval($value['begin_lesson']);
            }
            $courseList = json_decode($courseList, true);
            $arr_courseList = array_values($courseList);
        } else {
            foreach ($courseList as $key => $value) {
                $weeks = explode(',', $value['week']);
                $temp = [];
                for ($i = 0; $i < count($weeks) ; $i++) { 
                     array_push($temp, intval($weeks[$i]));
                 }
                $value['week'] = $temp;
                $value['hash_day'] = intval($value['hash_day']);
                $value['hash_lesson'] = intval($value['hash_lesson']);
                $value['begin_lesson'] = intval($value['begin_lesson']);
            }
            $arr_courseList = $courseList;
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'version' => env('APP_VERSION'),
            'data' => $arr_courseList,
            'nowWeek' => getNowWeek()
        ], 200);
    }

    public function getStuListByJxbID(Request $request)
    {
        //
        $jxbID = $request->get('jxbID');
        $user = $request->get('user');

        $slist_m = new SList();
        if (! $list = $slist_m->getStuListByJxbId($jxbID))
            return response()->json([
                'status' => 403,
                'message' => 'failed',
            ], 403);


        return response()->json([
            'status' => 200,
            'message' => 'success',
            'trid' => $user['trid'],
            'data_num' => count($list),
            'data' => $list
        ], 200);
    }

    public function checkAttendance(Request $request)
    {
        //
        $user = $request->get('user');
        $need = ['week', 'hash_day', 'hash_lesson', 'jxbID', 'status'];
        $info = $request->only($need);
        $week = $info['week'];

        $data = explode(',', $info['status']);

        $condition = [
            'trid' => $user['trid'],
            'jxbID' => $info['jxbID'],
            'hash_day' => $info['hash_day'],
            'hash_lesson' => $info['hash_lesson']
        ];

        $res1 = TCourse::where($condition)->select(['tcid', 'scNum', 'course'])->first();
        if (empty($res1['tcid']))
            return response()->json([
                'status' => 404,
                'message' => '这节课不存在'
            ], 404);

        $slist_m = new SList();
        if (! $list = $slist_m->getStuListByJxbId($info['jxbID']))
            return response()->json([
                'status' => 400,
                'message' => '考勤失败'
            ], 400);


        if (count($list) != count($data))
            return response()->json([
                'status' => 403,
                'message' => '状态参数数量与人数不一致'
            ], 403);

        $year = getCourseYear();
        $month = getNowMonth();
        $i = 0;

        foreach ($list as $key => $value) {
            $job_data = [
                'stuNum' => $value['stuNum'],
                'stuName' => $value['name'],
                'trid' => $user['trid'],
                'jxbID' => $info['jxbID'],
                'course' => $res1['course'],
                'year' => $year,
                'month' => $month,
                'week' => $week,
                'hash_day' => $info['hash_day'],
                'hash_lesson' => $info['hash_lesson'],
                'major' => $value['major'],
                'grade' => $value['grade'],
                'class' => $value['class'],
                'scNum' => $res1['scNum'],
                'status' => $data[$i]
            ];
            $i++;
            $this->dispatch(new checkAttendance($job_data));
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
        ], 200);
    }

    public function getAttendance(Request $request)
    {
        $info = $request->get('info');

        //获取所有符合条件的考勤信息
        $courseCheck_m = new CourseCheck();
        $res = $courseCheck_m->teaAttendance($info);
        if (!$res)
            return response()->json([
                'status' => 403,
                'message' => '获取考勤信息失败'
            ], 403);

        //如果res结果为空
        if (count($res) == 0) {
            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data_num' => 0,
                'data' => []
                ], 200);
        }
        //获取该教学班的人数
        $slist_m = new SList();

        $stu_num = $slist_m->getJxbStuNum($info['jxbID']);

        //将考勤信息统计
        $teacher_m = new Teacher();
        $statistics = $teacher_m->getStatistics($res, $stu_num, $info['status']);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data_num' => count($statistics),
            'data' => $statistics
        ], 200);

    }

    public function getCourseList(Request $request)
    {
        $year = getCourseYear();
        $user = $request->get('user');
        $week = 0;

        $course_m = new TCourse();
        if (! $courseList = $course_m->getCourse($user['trid'], $year)) {
            return response()->json([
                'status' => 400,
                'message' => 'failed',
                'data' => NULL,
            ], 400);
        }

        //简化课程信息，将不同的课程编号与之对应的课程名字返回
        $res = [];
        foreach ($courseList as $key => $value) {
            $res[$value['scNum']]['course'] = $value['course'];
            $res[$value['scNum']]['jxbID'] = [];
            array_push($res[$value['scNum']]['jxbID'], $value['jxbID']);
        }

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $res
        ], 200);
    }

    public function getStatistics(Request $request)
    {
        $year = getCourseYear();
        $week = getNowWeek();
        $user = $request->get('user');
        $hash_day = date('N', time()) - 1;

        $condition = [
            'year' => $year,
            'trid' => $user['trid'],
            'week' => $week
        ];

        $need = ['hash_day', 'status'];

        if (!$res = CourseCheck::where($condition)->select($need)->get())
            return response()->json([
                'status' => 400,
                'message' => 'failed',
                'data' => NULL
            ], 400);

        $count_week_sign = 0;
        $count_day_sign = 0;
        $count_week_absence = 0;
        $count_day_absence = 0;
        foreach ($res as $key => $value) {
            if ($value['status'] == env('SIGN')) {
                $count_week_sign++;
                if ($value['hash_day'] == $hash_day)
                    $count_day_sign++;
            } elseif ($value['status'] == env('ABSENCE')) {
                $count_week_absence++;
                if ($value['hash_day'] == $hash_day)
                    $count_day_absence++;
            }
        }

        $data = [
            'week_sign' => $count_week_sign,
            'week_absence' => $count_week_absence,
            'day_sign' => $count_day_sign,
            'day_absence' => $count_day_absence
        ];

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    public function getWeekStatistics(Request $request)
    {
        $user = $request->get('user');
        $info = $request->get('info');

        $course_check_m = new CourseCheck();
        if (!$res = $course_check_m->getWeekStatistics($info, $user['trid']))
            return response()->json([
                'status' => 400,
                'message' => 'failed'
            ], 400);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $res
        ], 200);
    }

    public function getMonthStatistics(Request $request)
    {
        $user = $request->get('user');
        $month = getNowMonth();

        $course_check_m = new CourseCheck();
        if (!$res = $course_check_m->getMonthStatistics($month, $user['trid']))
            return response()->json([
                'status' => 400,
                'message' => 'failed'
            ], 400);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $res
        ], 200);
    }

    public function getTermStatistics(Request $request)
    {
        $user = $request->get('user');
        $info = $request->get('info');

        $course_check_m = new CourseCheck();
        if (!$res = $course_check_m->getTermStatistics($info, $user['trid']))
            return response()->json([
                'status' => 400,
                'message' => 'failed'
            ], 400);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $res
        ], 200);
    }

    public function getStuList(Request $request)
    {
        $user = $request->get('user');
        $info = $request->get('info');

        $course_check_m = new CourseCheck();
        $res = $course_check_m->getStuList($info, $user['trid']);

        if (!$res)
            return response()->json([
                'status' => 400,
                'message' => 'failed',
            ], 400);

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'data' => $res
        ], 200);
    }

    public function getStuListExcel(Request $request)
    {
        $user = $request->get('user');
        $info = $request->get('info');
        $info['per_page'] = 9999;

        $course_check_m = new CourseCheck();
        $res = $course_check_m->getStuList($info, $user['trid']);

        if (!$res)
            return response()->json([
                'status' => 400,
                'message' => 'failed',
                'data' => NULL
            ], 400);

        $need = [
            'stuNum' => '学号',
            'stuName' => '姓名',
            'class' => '班级',
            'status' => '考勤状态',
            'created_at' => '考勤时间'
        ];
        $status = [
            env('SIGN') => '正常',
            env('LEAVE') => '请假',
            env('ABSENCE') => '旷到',
            env('LATE') => '迟到',
            env('LEAVE_EARLY') => '早退',
        ];
        $cellData = getExcelArray($res, $need, $status);

        return Excel::create('考勤信息',function($excel) use ($cellData){
                    $excel->sheet('attendance', function($sheet) use ($cellData){
                    $sheet->rows($cellData);
                    });
                })->export('xls');
    }

    public function setStuStatus(Request $request)
    {
        $need = ['ccid', 'status'];
        $user = $request->get('user');
        $info = $request->only($need);

        if (!is_numeric($info['status']))
            return response()->json([
                'status' => 400,
                'message' => 'failed'
            ], 400);

        $condition = [
            'ccid' => $info['ccid'],
            'trid' => $user['trid']
        ];
        $data = [
            'status' => $info['status']
        ];
        if (!CourseCheck::where($condition)->update($data))
            return response()->json([
                'status' => 404,
                'message' => 'Not Found'
            ], 404);

        return response()->json([
            'stauts' => 200,
            'message' => 'success'
        ], 200);
    }

    /**
     * 获取当前时间是第几周
     * return int
     */
    private function getNowWeek()
    {
        $term_start = strtotime(env('TERM_START'));
        $term_end = strtotime(env('TERM_END'));
        $now = time();
        $week = 604800;
        if ($now > $term_start && $now < $term_end)
            return (int)(($now - $term_start) / $week) + 1;

        return 0;
    }
}
