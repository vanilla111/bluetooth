<?php
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 2017/2/8
 * Time: 15:56
 */

if (! function_exists('test')) {
    function test()
    {
        return 'success';
    }
}

if (! function_exists('getCourseYear')) {
    function getCourseYear()
    {
        $time = time();
        $day = date('m-d', $time);
        $year = date('y', $time);
        $line_1 = '02-15';
        $line_2 = '08-01';

        if ($day > $line_1 && $day < $line_2)
            return $year . "1";
        else {
            $temp = substr($day, 0, 2);
            if ( $temp == "1" || $temp == "2" )
                $year -= 1;

            return $year . "2";
        }
    }
}

if (! function_exists('getNowMonth')) {
    function getNowMonth()
    {
        return date('n', time());
    }
}

if (! function_exists('getNowWeek')) {
    function getNowWeek()
    {
        $term_start = strtotime(env('TERM_START'));
        $now = time();

        $start_week = date("W", $term_start);
        $now_week = date("W", $now) + 1;

        return $now_week - $start_week;
    }
}

if (! function_exists('getExcelArray')) {
    function getExcelArray($data = [], $need = [], $status = [])
    {
        $excel_array = [];
        $column_key = [];
        foreach ($need as $k => $v) {
            array_push($column_key, $v);
        }
        array_push($excel_array, $column_key);

        for ($i = 0; $i < count($data); $i++) {
            $column_value = [];
            foreach ($data[$i]->toArray() as $k1 => $v1) {
                if (isset($need[$k1])) {
                    if ($k1 == 'status')
                        $v1 = $status[$v1];
                    array_push($column_value, $v1);
                }
            }
            array_push($excel_array, $column_value);
        }

        return $excel_array;
    }
}