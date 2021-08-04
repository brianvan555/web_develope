<?php
require_once("connMysqlObj.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
  header("Location: index.php");
}else{
$seslog = $_SESSION["loginMember"];
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="admin"){
header("Location: member_admin.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
  unset($_SESSION["loginMember"]);
  unset($_SESSION["memberLevel"]);
  header("Location: index.php");
}
//繫結登入會員資料
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
    <title>網站會員系統</title>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
  <style>
  .navbar{background-color: #FFA042;
    position: relative;   
    border: 2px solid transparent;} 
  .nav-stacked>li{font-size:18px;line-height: 50px;color: white;
  border-left: 1px;border-bottom:1px solid gray; 
  background: #4F4F4F;
  border-right: 1px }
  .nav-stacked>li>ul>li{font-size:18px;line-height: 40px;color: white;
  border-left: 1px;border-bottom:1px solid gray;
  border-right: 1px;}
  .nav-stacked>li>a{font-size:18px;line-height: 40px;color: white;}
  .nav-stacked>li>ul>li>a{font-size:18px;line-height: 40px;color: white;}
  .vc>ul>li>ul>li{list-style-type:none;}
  @media screen and (min-width:768px){ iframe {top: 0;left: 0;width:100%;height: 43em;} .vc{height:43em;}}
  @media screen and (min-width:1600px){ iframe {top: 0;left: 0;width:100%;height: 58em;} .vc{height:58em;}}
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
    <table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
      <!-- <tr>
        <td class="tdbline"><img src="images/logo_1.png" alt="華廈訓評" width="252" height="61"></td>
      </tr> -->
      <tr>
        <td class="tdbline">
          <table width="100%" border="0" cellspacing="0" cellpadding="10">
            <tr valign="top" class="regbox">
              <p class="title">歡迎蒞臨華廈會員系統</p>
              <!-- <td >
                <div align="center">
                  <p class="heading"><strong>會員專區</strong></p>
                  <p><strong><?php echo $row_RecMember["m_name"];?></strong> 您好。</p>
                  <p>您總共登入了 <?php echo $row_RecMember["m_login"];?> 次。<br>
                    本次登入的時間為：<br>
                  <?php echo $row_RecMember["m_logintime"];?></p>
                </div></td> -->
                <td>
                  <div align="center" class="col-xs-10 col-md-10 col-md-offset-1" style="padding:10px;">
                    <p><font size="3"><a href="../index.php">&nbsp; &nbsp;回到首頁</a> | <a href="form_d.php?userNO=<?php echo $row_RecMember['userNO']; ?>">報名表下載</a><br><br><a href="../page1.php">證照考試報名</a> | <a href="../page2&3.php">研習活動報名</a><br><br><a href="../course_userquery.php?userNO=<?php echo $row_RecMember['userNO']; ?>">榜單查詢</a> | <a href="../course_Reissue.php?userNO=<?php echo $row_RecMember['userNO']; ?>">證照補發</a><br><!-- <br><a href="?logout=true">登出系統</a> --></font></p>
                  </div></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td align="center" class="trademark"><font size="3">Copyright © 華廈訓評 2020</font></td>
            </tr>
          </table>
        </body>
      </html>
      <?php
        $db_link->close();
      ?>