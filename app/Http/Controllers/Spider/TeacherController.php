<?php

namespace App\Http\Controllers\Spider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TCourse;
use App\Models\SList;

class TeacherController extends Controller
{
    public $teaSearch_url = "http://jwzx.cqupt.edu.cn/jwzxtmp/data/json_teacherList.php?dirId=&";  //教师列表  page  rows

    //public $kebiao_url = "http://jwzx.cqupt.edu.cn/jwzxtmp/showKebiao.php?"; //课表查询 type id
    public $kebiao_url = "http://jwzx.cqupt.edu.cn/jwzxtmp/kebiao/kb_tea.php?teaId=";

    public $JxbStuList_url = "http://jwzx.cqupt.edu.cn/jwzxtmp/kebiao/kb_stuList.php?jxb="; //教学班学生名单  jxb

    public function getList()
    {
        $condition = 'page=1&rows=1648';
        $curlobj = curl_init();
        curl_setopt($curlobj, CURLOPT_URL, $this->teaSearch_url . $condition);
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        $output = curl_exec($curlobj);
        curl_close($curlobj);

        $teacher_data = json_decode($output, true);

        $teacher_m = new Teacher();
        foreach ($teacher_data['rows'] as $key => $value) {
            $data = [
                'trid' => $value['teaId'],
                'tName' => $value['teaName'],
                'tAcad' => $value['jys'],
                'AcadName' => $value['jysm'],
                'tMajor' => $value['yxm'],
                'tPosition' => $value['zc']
            ];
            $teacher_m->create($data);
        }
    }

    public function getCourse()
    {
        $day = ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'];
        $lesson = ['一二节', '三四节','五六节', '七八节', '九十节', '十一十二节'];
        $start = 1631;
        $end = 1647;
        $tea_m = new Teacher();
        $res = $tea_m->select('trid')->get();

        for ($i = $start; $i <= $end; $i++) {
            $trid = $res[$i]['trid'];
            $url = $this->kebiao_url . $trid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $pattern = "/<td[^>]+>([\s\S]*?)<\/td>/"; //匹配课程
            preg_match_all($pattern, $output, $match_result);

            //循环遍历抽出所有非空课程
            $pattern_1 = "/([\s\S]*?)<\/font/";
            $pattern_2 = "/<span[^>]+>([\s\S]*?)<\/span>/"; //课程类型
            $k = 8;
            $year = getCourseYear();
            $tCourse_m = new TCourse();
            for ($m = 0; $m <= 7; $m++) {
                if ($m > 2 && $m < 5)  //忽略中下午间隙
                    $hashLesson = $m - 1;
                elseif ($m > 5)
                    $hashLesson = $m - 2;
                else
                    $hashLesson = $m;

                for ($n = 0; $n <= 6; $n++) {
                    if (empty($match_result[1][$k])) {
                        //
                    } else {
                        $arr_0 = explode('<hr>', $match_result[1][$k]); //教师课程不同日期在同一时间的课程
                        if (count($arr_0) > 1) {
                            foreach ($arr_0 as $key => $value) {
                                $arr_1 = explode('<br>', $value);
                                preg_match_all($pattern_1, $arr_1[3], $match_result_1);
                                preg_match_all($pattern_2, $arr_1[4], $match_result_2);
                                $arr_2 = explode('-', $arr_1[1]);              //课程编号  名称
                                $arr_3 = explode(' ', $match_result_2[1][0]);    //教师姓名  课程类型
                                $arr_4 = explode('：', $arr_1[2]);
                                $arr_5 = $this->getWeek($match_result_1[1][0]);  //课程详细周数 课程持续时间
                                $temp = "<font color=#FF0000>";
                                $rawWeek = implode("", explode($temp, $match_result_1[1][0]));
                                $data = [
                                    'trid'        => $trid,                 //教师id
                                    'scNum'       => $arr_2[0],             //课程ID
                                    'jxbID'       => $arr_1[0],             //教学班ID
                                    'year'        => $year,                 //学年
                                    'hash_day'     => $n,                    //周几 - 1
                                    'hash_lesson'  => $hashLesson,           //第几大节课 - 1
                                    'begin_lesson' => $hashLesson * 2 + 1,   //课程开始节数
                                    'day'         => $day[$n],              //周几
                                    'lesson'      => $lesson[$hashLesson],  //课程时间
                                    'course'      => $arr_2[1],             //课程名称
                                    'teacher'     => $arr_3[0],             //教师姓名
                                    'type'        => $arr_3[1],             //课程类型
                                    'classroom'   => trim($arr_4[1]),       //上课地点
                                    'rawWeek'     => $rawWeek,              //课程周数
                                    'week'        => $arr_5['week'],        //课程具体周数
                                    'period'      => $arr_5['period']       //课程持续时间
                                ];
                                $tCourse_m->create($data);
                                //return response()->json($data);
                            }
                        } else {
                            $arr_1 = explode('<br>', $match_result[1][$k]);
                            preg_match_all($pattern_1, $arr_1[3], $match_result_1);
                            preg_match_all($pattern_2, $arr_1[4], $match_result_2);
                            $arr_2 = explode('-', $arr_1[1]);              //课程编号  名称
                            $arr_3 = explode(' ', $match_result_2[1][0]);    //教师姓名  课程类型
                            $arr_4 = explode('：', $arr_1[2]);
                            $arr_5 = $this->getWeek($match_result_1[1][0]);  //课程详细周数 课程持续时间
                            $temp = "<font color=#FF0000>";
                            $rawWeek = implode("", explode($temp, $match_result_1[1][0]));
                            $data = [
                                'trid'        => $trid,                 //教师id
                                'scNum'       => $arr_2[0],             //课程ID
                                'jxbID'       => $arr_1[0],             //教学班ID
                                'year'        => $year,                 //学年
                                'hash_day'     => $n,                    //周几 - 1
                                'hash_lesson'  => $hashLesson,           //第几大节课 - 1
                                'begin_lesson' => $hashLesson * 2 + 1,   //课程开始节数
                                'day'         => $day[$n],              //周几
                                'lesson'      => $lesson[$hashLesson],  //课程时间
                                'course'      => $arr_2[1],             //课程名称
                                'teacher'     => $arr_3[0],             //教师姓名
                                'type'        => $arr_3[1],             //课程类型
                                'classroom'   => trim($arr_4[1]),       //上课地点
                                'rawWeek'     => $rawWeek,              //课程周数
                                'week'        => $arr_5['week'],        //课程具体周数
                                'period'      => $arr_5['period']       //课程持续时间
                            ];
                            $tCourse_m->create($data);
                            //return response()->json($data);
                        }
                    }
                    $k++;
                }
                $k++;
            }
        }
    }

    public function getNewCourse()
    {
        $day = ['星期一', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'];
        $lesson = ['一二节', '三四节','五六节', '七八节', '九十节', '十一十二节'];
        $start = 154;
        $end = 154;
        $tea_m = new Teacher();
        $res = $tea_m->select('trid')->get();

        for ($i = $start; $i <= $end; $i++) {
            $trid = $res[$i]['trid'];
            $url = $this->kebiao_url . $trid;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $output = curl_exec($ch);
            curl_close($ch);
            $pattern = "/<td[^>]+>([\s\S]*?)<\/td>/"; //匹配课程
            preg_match_all($pattern, $output, $match_result);

            //按列表查询抓取数据

        }
    }

    public function getStuList()
    {
        $stu_list_m = new SList();
        $tCourse_m = new TCourse();
        $start = 12641;
        $end = 12652;
        $year = getCourseYear();
        for ($i = $start; $i <= $end; $i++) {
            $res = $tCourse_m->select('jxbID')->where('tcid', '=', $i)->first();
            //echo $res['jxbID'] . '<br>';
            if (!$stu_list_m->select('jxbID')
                ->where('jxbID', '=', $res['jxbID'])
                ->where('year', '=', $year)
                ->exists()) {
                $curlobj = curl_init();
                curl_setopt($curlobj, CURLOPT_URL, $this->JxbStuList_url . $res['jxbID']);
                curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curlobj, CURLOPT_HEADER, 0);
                $output = curl_exec($curlobj);
                curl_close($curlobj);

                $pattern = "/<td>([\s\S]*?)<\/td>/"; //匹配课程
                preg_match_all($pattern, $output, $match_result);

                $list_arr = array_slice($match_result[1], 12);
                //return count($list_arr);
                for ($j = 0; $j < count($list_arr) / 12; $j++) {
                    $m = $j * 12;
                    $stu_list[$j]['stuNum'] = $list_arr[$m + 1];
                    $stu_list[$j]['name'] = $list_arr[$m + 2];
                    $stu_list[$j]['gender'] = $list_arr[$m + 3];
                    $stu_list[$j]['class'] = $list_arr[$m + 4];
                    $stu_list[$j]['major'] = $list_arr[$m + 5];
                    $stu_list[$j]['majorName'] = $list_arr[$m + 6];
                    $stu_list[$j]['academy'] = $list_arr[$m + 7];
                    $stu_list[$j]['grade'] = $list_arr[$m + 8];
                    $stu_list[$j]['type'] = $list_arr[$m + 9];
                }
                $data = [
                    'jxbID'    => $res['jxbID'],
                    'year'     => $year,
                    'stu_list' => serialize($stu_list),
                ];
                $stu_list_m->create($data);
            } else {
                //
            }
        }

        return 'OK!!!';
    }

    private function getWeek($str)
    {
        $week = [];
        $arr = explode(',', $str);
        $period = 2;
        foreach ($arr as $key => $value) {
            $arr_0 = explode('周', $value);
            if (count($arr_0) > 1) {
                $arr_1 = explode('-', $arr_0[0]);
                if (count($arr_1) > 1) {
                    for ($i = $arr_1[0]; $i <= $arr_1[1]; $i++) {
                        array_push($week, $i);
                        if ($arr_0[1] == "单" || $arr_0[1] == "双") {
                            $i++;
                        }
                    }
                } else {
                    array_push($week, $arr_1[0]);
                }
                if (!empty($arr_0[1])) {
                    $temp = explode("<font color=#FF0000>", $arr_0[1]);
                    if (!empty($temp[1])) {
                        if ($temp[1] == "4节连上")
                            $period = 4;
                        elseif ($temp[1] == "3节连上")
                            $period = 3;
                    }
                }
            } else {
                $arr_1 = explode('-', $arr_0[0]);
                if (count($arr_1) > 1) {
                    for ($i = $arr_1[0]; $i <= $arr_1[1]; $i++) {
                        array_push($week, $i);
                    }
                } else {
                    array_push($week, $arr_1[0]);
                }
                $period = 2;
            }
        }
        $res = [
            'week' => implode(',', $week),
            'period' => $period
        ];

        return $res;
    }

}
