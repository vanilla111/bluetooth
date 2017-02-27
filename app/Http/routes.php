<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web'], 'prefix' => 'api'], function () {
    Route::any('/test', 'Teacher\TeacherController@test');
    //教师操作
    Route::group(['prefix' => 'teacher', 'namespace' => 'Teacher'], function () {
        Route::post('/login', 'TeacherController@login');//登录
        Route::group(['middleware' => ['jwt.teacher']], function () {
            Route::get('/course', 'TeacherController@getCourse');//获取教师课程表
            Route::get('/stulist', 'TeacherController@getStuListByJxbID');//获取指定教学班的学生名单
            Route::post('/attendance', 'TeacherController@checkAttendance');//提交考勤数据
            Route::get('/attendance', 'TeacherController@getAttendance')->middleware('tea.attendance');//获取考勤数据
            Route::group(['prefix' => 'web'], function () {
                Route::get('/courselist', 'TeacherController@getCourseList');
                Route::get('/statistics', 'TeacherController@getStatistics');
                Route::get('/weekstatistics', 'TeacherController@getWeekStatistics')->middleware('tea.statistics');
                Route::get('/monthstatistics', 'TeacherController@getMonthStatistics');
                Route::get('/stulist', 'TeacherController@getStuList')->middleware('tea.stu.list');
            });
        });
    });
    //学生操作
    Route::group(['prefix' => 'student', 'namespace' => 'Student'], function () {
        Route::post('/login', 'StudentController@login');//登录
        Route::group(['middleware' => ['jwt.student']], function () {
            Route::get('/course', 'StudentController@getCourse');//获取学生课表
            Route::get('/attendance', 'StudentController@getAttendance')->middleware('stu.attendance');//获取学生本人的考勤信息
        });
    });
});
