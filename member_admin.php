<?php
ini_set('display_errors', 'off');
session_start();
require_once("connMysqlObj.php");
date_default_timezone_set('Asia/Taipei');//設定台北時區
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
header("Location: index.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
unset($_SESSION["loginMember"]);
unset($_SESSION["memberLevel"]);
header("Location: index.php");
}
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
$stmt=$db_link->prepare($query_delMember);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$stmt->close();
//重新導向回到主畫面
header("Location: member_admin.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE userNO=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<title>華廈訓評後台管理</title>
</head>
<style>
	.navbar{
		background-color:#FFA042;
		position:relative;		
		border:2px solid transparent;
	}
	.nav-stacked{
		width:150px;
	}
	.nav-stacked>li{
		font-size:18px;
		line-height:50px;
		color:white;
		border-left:1px;
		border-bottom:1px solid gray;
		background:#4F4F4F;
		border-right:1px;
	}
	.nav-stacked>li:hover{
		color:cyan;
	}
	.nav-stacked>li>ul>li{
		font-size:15px;
		line-height:40px;
		color:white;
		border-left:1px;
		border-bottom:1px solid gray;
		border-right:1px;
	}.nav-stacked>li>ul>li:hover{
		color:blue;
	}
	.nav-stacked>li>a{
		font-size:18px;
		line-height:40px;
		color:white;
	}
	.nav-stacked>li>a:hover{
		color: black;
	}
	.nav-stacked>li>ul>li>a{
		font-size:15px;
		line-height:40px;
		color: white;
	}
	.nav-stacked>li>ul>li>a:hover{
		color: yellow;
	}
	.vc>li>ul>li{
		list-style-type:none;
	}
	@media screen and (min-width:768px){
		iframe{top:0;
			left:0;
			width:105%;
			height:43em;
			margin-left: 3%;
		}
		.vc{
			height:43em;
		}
	}
	@media screen and (min-width:1700px){ iframe {top: 0;left: 0;width:105%;height: 58em;margin-left: 4%;} .vc{height:58em;}}
</style>
<script>
	function rif(){
		var x = document.getElementsByTagName("iframe");
		var i;
		for (i = 0; i < x.length; i++) {
		  x[i].height = screen.availHeight*0.69;
		  x[i].width = screen.availWidth*0.875;
		}
	}
</script>
<body>
	<nav class="navbar" role="navigation">
		<div class="container-fluid">	
			<div class="navbar-header">
				<a class="navbar" href="../index.php">
				<img src="../img/logo.png" width="250">
			</a>	
			</div>
			<div align="right">
				<p>管理者 您好</p><?php echo '<p id="time"></p><script type="text/javascript">
			var dayNames = new Array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
				function get_obj(time){
					return document.getElementById(time);
				}
				var ts='.(round(microtime(true)*1000)).';
				function getTime(){
					var t=new Date(ts);
					with(t){
						var _time="台北時間: "+getFullYear()+"-" + (getMonth()+1)+"-"+getDate()+" " + (getHours()<10 ? "0" :"") + getHours() + ":" + (getMinutes()<10 ? "0" :"") + getMinutes() + ":" + (getSeconds()<10 ? "0" :"") + getSeconds() + " " + dayNames[t.getDay()];
					}
					get_obj("time").innerHTML=_time;
					setTimeout("getTime()",1000);
					ts+=1000;
				}
				getTime();
		</script>'; ?><p><a href="?logout=true"><button class="btn btn-danger">登出系統</button></a></p>
			</div>
		</div>
	</nav>
	<div class="container-fluid" style="margin-top:-1%;">
		<ul class="nav nav-stacked col-xs-2 col-md-10 col-lg-2 vc" style="background-color: #4F4F4F;margin-left: -1%;">
			<li class="h0"><a href="#h0">會員管理</a></li>
			<li class="h1"><a href="#h1">行事曆</a></li>
        	<li data-toggle="collapse" data-target="#place"><a href="#">場租管理<span class="caret"></span></a>
        		<ul id="place" class="collapse place">
	            	<li><a href="#p1">教室資訊管理</a></li>
	                <li><a href="#p2">待審場租列表</a></li>
	                <li><a href="#p3">已審場租列表</a></li>
            	</ul>
            </li>
            <li data-toggle="collapse" data-target="#semi"><a href="#">研習課程<span class="caret"></span></a>
            	<ul id="semi" class="collapse place">
	            	<li><a href="#h2">研習課程管理</a></li>
	                <li><a href="#h21">研習報名管理</a></li>
            	</ul>
            </li>
            <li class="h3"><a href="#h3">師資管理</a></li>
            <li data-toggle="collapse" data-target="#clas"><a href="#">證照課程<span class="caret"></span></a>
                <ul id="clas" class="collapse clas">
                	<li><a href="#c1">課程管理系統</a></li>
                    <li><a href="#c2">考試結果登錄</a></li>
                    <li><a href="#c3">成績開放管理</a></li>
                    <li><a href="#c4">證照補發管理</a></li>
                    <li><a href="#c5">考生查詢</a></li>                     
                </ul>
            </li>
		</ul>	

		<div class="col-xs-10 col-md-10 col-lg-10 tab-inner" id="h0">
			<iframe src="memberlist.php" onload="rif()"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="h1">
			<iframe src="open_calender.php"></iframe>
		</div>				
		<div class="col-md-10 tab-inner" id="p1">
			<iframe src="img_admin.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="p2">
			<iframe src="mailorderlist.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="p3">
			<iframe src="orderlist.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="h2">
			<iframe src="admin_meeting.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="h21">
			<iframe src="seminar_admin.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="h3">
			<iframe src="admin_teacher.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="c1">
			<iframe src="course_admin.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="c2">
			<iframe src="s_entry.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="c3">
			<iframe src="decision.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="c4">
			<iframe src="../mailrelist.php"></iframe>
		</div>
		<div class="col-md-10 tab-inner" id="c5">
			<iframe src="s_history.php"></iframe>
		</div>		
	</div>
</body>
<script>
	$(function(){
    var $li0 = $('li.h0');
    var $li1 = $('li.h1');
    var $li2 = $('ul.semi li');
    var $li3 = $('li.h3');
    var $lili = $('ul.place li');
    var $lli = $('ul.clas li');
        $($li0.addClass('active').find('a').attr('href')).siblings('.tab-inner').hide();
    	
    	$li0.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        }); 
        $li1.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        });
        $li2.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        });
        $li3.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        });                
        $lili.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        });
        $lli.click(function(){
            $($(this).find('a'). attr ('href')).show().siblings ('.tab-inner').hide();
            $(this).addClass('active'). siblings ('.active').removeClass('active');
        });
    });
</script>
</html>
<?php
  $db_link->close();
?>