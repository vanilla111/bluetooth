window.onload = function () {


	var allData = {};

	var URL = "";
	var token = document.cookie;

	var courseList;
	var scNum = [];
	var scName =[];
	$.get(URL + "./api/teacher/web/courselist?" + token, function(result){
		if (result.status == 200) {
			courseList = result.data;
			for (a in courseList) {
				scNum.push(a);
				scName.push(courseList[a]);
				allData[a] = courseList[a];
			}
			var course = "";
			for(var i = 0; i < scNum.length ;i++) {
				course += "<li><a>" + scName[i] + "</a></li>"
			}
			$(".courseList").map(function (index,value) {
				value.innerHTML = course;
			})
		}
		
	});

	$.get(URL + "./api/teacher/web/statistics?" + token, function(result){
		if (result.status == 200) {
			$("#w-mark")[0].innerHTML = result.data.week_sign;
			$("#w-no")[0].innerHTML = result.data.week_absence;
			$("#d-mark")[0].innerHTML = result.data.day_sign;
			$("#d-no")[0].innerHTML = result.data.day_absence;
			$("#d-mark-r")[0].innerHTML = Number(100*result.data.day_sign/(result.data.day_sign+result.data.day_absence)).toFixed(3) + "%";
			$("#d-no-r")[0].innerHTML = Number(100*result.data.day_absence/(result.data.day_sign+result.data.day_absence)).toFixed(3) + "%";
		}
		
	});

	var chart = echarts.init(document.getElementById('flot-chart1'));
	var chartData = {
		xAxis: [],
		data: []
	};
	$.get(URL + "./api/teacher/web/monthstatistics?" + token, function(result){
		if (result.status == 200) {
			for(var i = 0; i<result.data.length; i++) {
				chartData.xAxis.push(i+1);
			}
			chartData.data = result.data;

			var option = {
				tooltip : {
					trigger: 'axis'
				},
				grid: {
					left: '3%',
					right: '4%',
					bottom: '2%',
					top: '8%',
					containLabel: true
				},
				xAxis : [
				{
					type : 'category',
					boundaryGap : false,
					data : chartData.xAxis
				}
				],
				yAxis : [
				{
					type : 'value'
				}
				],
				series : [
				{
					name:'旷到人数',
					type:'line',
					stack: '总量',
					areaStyle: {normal: {}},
					data: chartData.data
				}
				]
			};
			chart.setOption(option);

		}	
	});

	function changeNum(name) {{
		for (id in allData ) {
			if (allData[id] == name) {
				return id;
			}
		}
	}}

	function changeTag(id,tag) {
		id = "#" + id;
		tag = "#" + tag;
		id = $(id)[0];
		document.querySelector(tag).addEventListener("click",function(e) {
			id.textContent = e.target.textContent;
		})
	}

	changeTag("grade-m","grade-m-list");
	changeTag("scNum-m","scNum-m-list");
	changeTag("grade","grade-list");
	changeTag("scNum","scNum-list");



	var myChart = echarts.init(document.getElementById('lineChart'));
	var myChartData = {
		leave: [],
		late: [],
		absence: []
	}


	$("#ok-m").click(function () {
		$.get(URL + "./api/teacher/web/weekstatistics?" + token
			+ "&grade=" + Number($("#grade-m")[0].textContent)
			+ "&scNum=" + changeNum($("#scNum-m")[0].textContent)
			, function(result){
		if (result.status == 200) {
				myChartData.leave = result.data.leave;
				myChartData.late = result.data.late;
				myChartData.absence = result.data.absence;

				var option = {
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						data:['早退人数','迟到人数','旷到人数',],
						itemWidth: 15,
						itemHeight: 10,
					},
					toolbox: {
						feature: {
							saveAsImage: {}
						}
					},
					grid: {
						left: '3%',
						right: '4%',
						bottom: '3%',
						top: '15%',
						containLabel: true
					},
					xAxis : [
					{
						type : 'category',
						boundaryGap : false,
						data : ['周一','周二','周三','周四','周五','周六','周日']
					}
					],
					yAxis : [
					{
						type : 'value'
					}
					],
					series : [
					{
						name:'早退人数',
						type:'line',
						stack: '总量',
						areaStyle: {normal: {}},
						data: myChartData.leave
					},
					{
						name:'迟到人数',
						type:'line',
						stack: '总量',
						areaStyle: {normal: {}},
						data: myChartData.late
					},
					{
						name:'旷到人数',
						type:'line',
						stack: '总量',
						areaStyle: {normal: {}},
						data: myChartData.absence
					}
					]
				};

				myChart.setOption(option);

			}
		});
	})

	var page = 0;
	day = false;
	month = false;
	var course = "";

	$.get(URL + "./api/teacher/web/stulist?" + token
		+ "&page=" + page 
		+ "&per_page=" + 20
		+ "&grade=" + Number($("#grade")[0].textContent)
		+ "&scNum=" + changeNum($("#scNum")[0].textContent)
		+ "&today=" + day 
		+ "&this_month=" + month 
		+ "&status=" + 3 
		, function(result){
		if (result.status == 200) {
			for(var i = 0; i < result.data.length ;i++) {
				course = course + "<tr id = " + result.data[0].ccid + "><td>" + result.data[0].stuName + 
				"</td><td><span class='pie'>" + result.data[0].class + 
				"</span></td><td>" + result.data[0].stuNum + 
				"</td><td>" + result.data[0].created_at + 
				"</td><td><button id='4' type='button' class='btn btn-primary btn-xs'>迟到</button><button id='2' type='button' class='btn btn-primary btn-xs'>请假</button></td></tr>"
			}
			$(course).appendTo("tbody");
		}
		
	});

	$("#day").click(function () {	
		day = true;
		month = false;
	})
	$("#week").click(function () {
		day = false;
		month = false;
	})
	$("#month").click(function () {
		day = true;
		month = true;
	})

	$("#more").click(function () {
		page++;
		$.get(URL + "./api/teacher/web/stulist?" + token
			+ "&page=" + page 
			+ "&per_page=" + 20 
			+ "&grade=" + Number($("#grade")[0].textContent)
			+ "&scNum=" + changeNum($("#scNum")[0].textContent)
			+ "&today=" + day 
			+ "&this_month=" + month 
			+ "&status=" + 3 
			, function(result){
			if (result.status == 200) {
				alert("没有了");
				for(var i = 0; i < result.data.length ;i++) {
					ccourse = course + "<tr id = " + result.data[0].ccid + "><td>" + result.data[0].stuName + 
					"</td><td><span class='pie'>" + result.data[0].class + 
					"</span></td><td>" + result.data[0].stuNum + 
					"</td><td>" + result.data[0].created_at + 
					"</td><td><button id='4' type='button' class='btn btn-primary btn-xs'>迟到</button><button id='2' type='button' class='btn btn-primary btn-xs'>请假</button></td></tr>"
				}
				$(course).appendTo("tbody");
			}
			
		});
	})

	$("#search").click(function () {
		$.get(URL + "./api/teacher/web/stulist?" + token
			+ "&page=" + page 
			+ "&per_page=" + 20 
			+ "&grade=" + Number($("#grade")[0].textContent)
			+ "&scNum=" + changeNum($("#scNum")[0].textContent)
			+ "&today=" + day 
			+ "&this_month=" + month 
			+ "&status=" + 3 
			, function(result){
			if (result.status == 200) {
				if(result.total == 0) {
					alert("没有了");
				}
				for(var i = 0; i < result.data.length ;i++) {
					course = course + "<tr id = " + result.data[0].ccid + "><td>" + result.data[0].stuName + 
					"</td><td><span class='pie'>" + result.data[0].class + 
					"</span></td><td>" + result.data[0].stuNum + 
					"</td><td>" + result.data[0].created_at + 
					"</td><td><button id='4' type='button' class='btn btn-primary btn-xs'>迟到</button><button id='2' type='button' class='btn btn-primary btn-xs'>请假</button></td></tr>"
				}
				$(course).appendTo("tbody");
			}
		});
	})

	$("tr").click(function(e) {
		if (e.currentTarget.id && e.target.id) {
			$.ajax({
				url: URL + "./api/teacher/web/stulist?" + token
					+ "&ccid=" + e.currentTarget.id 
					+ "&status=" + e.target.id ,
				type: 'PUT',
				success: function(response) {
					$("#"+e.currentTarget.id)[0].innerHTML = "";
				}
			});
		}
	})

}