window.onload = function () {

	var from = $('#sub');
	var token;
	var ip = "http://123.207.9.187";

	from.click(function (e) {
		var data = {};
		data.trid = $('#trid')[0].value;
		data.password = $('#password')[0].value;
		$.post(ip + "/api/teacher/login",data,
			function(result){
	    		if(result.status == 200) {
	    			data = result.data;
	    			setCookie('token',data,1);
	    			window.location = "./index"
	    		}
	  		});
	});

	function setCookie(c_name,value,expiredays)
	{
		var exdate=new Date();
		console.log(c_name,value,expiredays);
		exdate.setDate(exdate.getDate()+expiredays);
		document.cookie=c_name+ "=" +escape(value)+
		((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
		console.log(escape(value));
	}

}