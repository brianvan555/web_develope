<?php
require_once("connMysqlObj.php");
session_start();
//check login or not
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
  header("Location: index.php");
}else{
$seslog = $_SESSION["loginMember"];
}
//check permission level
if($_SESSION["memberLevel"]=="admin"){
header("Location: member_admin.php");
}
//logout
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
  unset($_SESSION["loginMember"]);
  unset($_SESSION["memberLevel"]);
  header("Location: index.php");
}
//get member data
$query_RecMember = "SELECT * FROM memberdata WHERE userNO = '{$_SESSION["loginMember"]}'";
  $RecMember = $db_link->query($query_RecMember);
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
  <head>
    <meta charset="utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="//code.jquery.com/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <title>backstage_member</title>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
  <style>
   .navbar{
    background-color:#FFA042;
    position:relative;    
    border:2px solid transparent;
  }
.smalltext {
  font-size: 14px;
  color:  #804040;
  font-family: "微軟正黑體";
  vertical-align: middle;
}
@media screen and (min-width:768px){.vc{height:40em;}}
  @media screen and (min-width:1700px){.vc{height:55em;}}  
body{ font-size: 20px;}
.footer{
    font-size:20px;
  }
  .btn-sm{
    border-radius: 20px;
    font-color:white;
  }
</style>
  <body>
    <nav class="navbar" role="navigation">
    <div class="container-fluid"> 
      <div class="navbar-header">
        <a class="navbar" href="../index.php">
        <img src="../img/logo.png" width="250">
      </a>  
      </div>
      <div align="right" style="padding:10px;">
        <p><font size="2"><strong><?php echo $row_RecMember["m_name"];?></strong> 您好</p>
        <?php echo '<p id="time"></p><script type="text/javascript">
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
    </script>'; ?><p><a href="?logout=true"><button class="btn btn-danger">登出系統</button></a></font></p>
      </div>
    </div>
  </nav>
<div class="container">
  <h1 align="center"><font color="red"><b>歡迎蒞臨華廈會員系統</b></font></h1>
<div class="row col-xs-12 col-md-offset-2 col-md-8" style="margin-top: 10px">
<div class="col-xs-7 col-md-4" align="center" style="margin-top: 10px;">
  <a href="../index.php" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>&nbsp;&nbsp;回到首頁&nbsp;&nbsp;</h2></font><br></a>
</div>
<div class="col-xs-5 col-md-4" align="center" style="margin-top: 10px;">
  <a href="form_d.php?userNO=<?php echo $row_RecMember['userNO']; ?>" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>報名表下載</h2></font><br></a>
</div>
<div class="col-xs-7 col-md-4" align="center" style="margin-top: 10px">
  <a href="../page1.php" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>&nbsp;&nbsp;考照報名&nbsp;&nbsp;</h2></font><br></a>
</div>
<div class="col-xs-5 col-md-4" align="center" style="margin-top: 10px;">
  <a href="../page2&3.php" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>&nbsp;&nbsp;研習報名&nbsp;&nbsp;</h2></font><br></a>
</div>
<div class="col-xs-7 col-md-4" align="center" style="margin-top: 10px;">
  <a href="../course_userquery.php?userNO=<?php echo $row_RecMember['userNO']; ?>" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>&nbsp;&nbsp;榜單查詢&nbsp;&nbsp;</h2></font><br></a>
</div>
<div class="col-xs-5 col-md-4" align="center" style="margin-top: 10px">
  <a href="../course_Reissue.php?userNO=<?php echo $row_RecMember['userNO']; ?>" class="btn btn-primary btn-sm" style="">
    <font color="white"><h2>&nbsp;&nbsp;證照補發&nbsp;&nbsp;</h2></font><br></a>
</div>
</div>
<div class="row col-xs-12 col-md-12" style="margin-top: 5%"><!--footer-->
<footer>
<div class="col-md-12 col-xs-12"  align="center">
<p class="footer">地址：<br>
  服務專線： <a href="tel:04-00000000">04-0000-0000
  </a><br>
  <font color="#4187f0">Copyright © Brian Liu 2020</font>
</p>
</div>
</footer>
    </div>
        </div>
        </body>

      </html>
      <?php
        $db_link->close();
      ?>