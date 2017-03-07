<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>bluetoor</title>

    <link href="css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css?v=2.2.0" rel="stylesheet">
    <style>
        .table .btn {
            margin-left: 5px;
        }
    </style>
</head>

<body class="top-navigation">
<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            <nav class="navbar navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                        <i class="fa fa-reorder"></i>
                    </button>
                    <a href="index.html#" class="navbar-brand">Bluetoor</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="login.html">
                                <i class="fa fa-sign-out"></i> 退出
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-success pull-right">周</span>
                                <h5>签到人数</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 id="w-mark" class="no-margins"></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-info pull-right">周</span>
                                <h5>未到人数</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 id="w-no" class="no-margins"></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <span class="label label-primary pull-right">今天</span>
                                <h5>签到状况</h5>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h1 id="d-mark" class="no-margins"></h1>
                                        <div id="d-mark-r" class="font-bold text-navy"> <i class="fa fa-bolt"></i>  <small>签到人数</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h1 id="d-no" class="no-margins"></h1>
                                        <div id="d-no-r" class="font-bold text-navy"> <i class="fa fa-bolt"></i>  <small>旷到人数</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>月签到状况</h5>
                                <div class="ibox-tools">
                                    <span class="label label-primary">2017.02 更新</span>
                                </div>
                            </div>
                            <div class="ibox-content no-padding">
                                <div class="flot-chart m-t-lg" style="height: 90px; margin-top: 0;">
                                    <div class="flot-chart-content" id="flot-chart1"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-content">
                                <div>
                                    <div class="ibox-tools">
                                        <div class="btn-group">
                                            <div id="grade-m" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">年级 <span class="caret"></span>
                                            </div>
                                            <ul id="grade-m-list" class="dropdown-menu">
                                                <li><a>2014</a>
                                                </li>
                                                <li><a>2015</a>
                                                </li>
                                                <li><a>2016</a>
                                                </li>
                                                <li><a>2017</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="btn-group">
                                            <button id="scNum-m" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">课程 <span class="caret"></span>
                                            </button>
                                            <ul id="scNum-m-list" class="dropdown-menu courseList"></ul>
                                        </div>
                                        <div class="btn-group">
                                            <button id="ok-m" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">确定</button>
                                        </div>
                                    </div>
                                    <h3 class="font-bold no-margins">
                                        一周旷到率
                                    </h3>
                                </div>

                                <div class="m-t-sm">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                                <div id="lineChart" style="height: 200px"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="m-t-md">
                                    <small class="pull-right">
                                        <i class="fa fa-clock-o"> </i>
                                        2017.02.30更新
                                    </small>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>旷到学生</h5>
                                <div class="ibox-tools">
                                    <div class="btn-group">
                                        <button id="grade" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">年级 <span class="caret"></span>
                                        </button>
                                        <ul id="grade-list" class="dropdown-menu">
                                            <li><a>2014</a>
                                            </li>
                                            <li><a>2015</a>
                                            </li>
                                            <li><a>2016</a>
                                            </li>
                                            <li><a>2017</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button id="scNum" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">课程 <span class="caret"></span>
                                        </button>
                                        <ul id="scNum-list" class="dropdown-menu courseList"></ul>
                                    </div>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <div class="row">
                                    <div class="col-sm-9 m-b-xs">
                                        <div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-sm btn-white">
                                            <input type="radio" id="day" name="options">天</label>
                                            <label class="btn btn-sm btn-white active">
                                            <input type="radio" id="week" name="options">周</label>
                                            <label class="btn btn-sm btn-white">
                                            <input type="radio" id="month" name="options">月</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input type="text" placeholder="搜索" class="input-sm form-control"> <span class="input-group-btn">
                                            <button id="search" type="button" class="btn btn-sm btn-primary">搜索</button> </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>姓名</th>
                                                <th>班级</th>
                                                <th>学号</th>
                                                <th>日期</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="stulist"></tbody>
                                    </table>
                                </div>
                                <button id="more" type="button" style="margin: 0 auto; display:block;" class="btn btn-primary btn-s">更多</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mainly scripts -->
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/bootstrap.min.js?v=3.4.0"></script>
<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="js/plugins/echarts/echarts.js"></script>
<script src="js/index.js"></script>

</body>
</html>

