<?php
session_start();
require_once("connMysqlObj.php");
date_default_timezone_set('Asia/Taipei');//設定台北時區
$time=date("Y-m-d H:i:s");
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
  //若帳號等級為 member 則導向會員中心
  if($_SESSION["memberLevel"]=="member"){
    header("Location: member_center.php");
  //否則則導向管理中心
  }else{
    header("Location: member_admin.php"); 
  }
}
//執行會員登入
if(isset($_POST["userNO"]) && isset($_POST["passwd"])){
  //繫結登入會員資料
  $query_RecLogin = "SELECT userNO, m_passwd, m_level FROM memberdata WHERE userNO=?";
  $stmt=$db_link->prepare($query_RecLogin);
  $stmt->bind_param("s", $_POST["userNO"]);
  $stmt->execute();
  //取出帳號密碼的值綁定結果
  $stmt->bind_result($userNO, $passwd, $level); 
  $stmt->fetch();
  $stmt->close();
  //比對密碼，若登入成功則呈現登入狀態
  if(password_verify($_POST["passwd"],$passwd)){
    //計算登入次數及更新登入時間
    $query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime='$time' WHERE userNO=?";
    $stmt=$db_link->prepare($query_RecLoginUpdate);
      $stmt->bind_param("s", $userNO);
      $stmt->execute(); 
      $stmt->close();
    //設定登入者的名稱及等級
    $_SESSION["loginMember"]=$userNO;
    $_SESSION["memberLevel"]=$level;
    //使用Cookie記錄登入資料
    if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
      setcookie("remUser", $_POST["userNO"], time()+365*24*60);
      setcookie("remPass", $_POST["passwd"], time()+365*24*60);
    }else{
      if(isset($_COOKIE["remUser"])){
        setcookie("remUser", $_POST["userNO"], time()-100);
        setcookie("remPass", $_POST["passwd"], time()-100);
      }
    }
    //若帳號等級為 member 則導向會員中心
    if($_SESSION["memberLevel"]=="member"){
      header("Location: member_center.php");
    //否則則導向管理中心
    }else{
      header("Location: member_admin.php"); 
    }
  }else{
    header("Location: index.php?errMsg=1");
  }
}
?>
<html>
<head>
<meta charset="utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="//code.jquery.com/jquery.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<title>華廈訓評會員系統</title>
</head>
<style>
  .navbar{
    background-color:#FFA042;
    position:relative;    
    border:2px solid transparent;
  }
  .errDiv {
  font-family: "微軟正黑體";
  font-size: 15px;
  color: #FFFFFF;
  background-color: #FF0000;
  padding: 4px;
  text-align: center;
}
@media screen and (min-width:768px){.vc{height:40em;}}
  @media screen and (min-width:1700px){.vc{height:55em;}}  
body{ font-size: 15px;}
</style>
<body>
  <nav class="navbar" role="navigation">
    <div class="container-fluid"> 
      <div class="navbar-header">
        <a class="navbar" href="../index.php">
        <img src="../img/logo.png" width="250">
      </a>  
      </div>
    </div>
  </nav>
  <div class="container"> 
    <div class="col-md-offset-4 col-md-4" style="background-color: #BEBEBE"> 
          <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
      <h2 align="center">會員登入</h2><hr>
      <div class="row">
      <form name="form1" method="post" action="">
        <p>
          <label for="userNO" class="col-md-offset-1 col-md-3" style="margin-top: 4px;"><font size="4px">帳號:</font></label>
            <input name="userNO" type="text" id="userNO" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">          
        </p>
        <p>
          <label for="userNO" class="col-md-offset-1 col-md-3" style="margin-top: 4px;"><font size="4px">密碼:</font></label>
            <input  name="passwd" type="password" id="passwd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">          
        <p>
        <div class="col-md-12" align="center">
          <input name="rememberme" type="checkbox" id="rememberme" value="true" checked>記住我的帳號密碼<br>
          <input type="button" value="忘記密碼" onclick="location.href='admin_passmail.php'">
          <input type="submit" name="button" id="button" value="登入系統">
        </div>
      </form>
      </div>
      <hr>
        <div align="center">        
          <a href="member_join.php">快速註冊</a>&nbsp;&nbsp;
          <a href="../index.php">回首頁</a>
        </div>
      </div> 
    <div class="row col-md-12" style="margin-top: 5%"><!--footer-->
      <footer>
      <div class="col-md-offset-3 col-md-3 col-xs-8">
      <p>406台中市北屯區北屯路366號15樓<br>TEL: <a href="tel:04-22468816">04-2246-8816</a>
      </p>
      </div>
      <div class="col-md-3 col-xs-4" align="right">
      <p><font color="#4187f0" size="3px">Copyright © 華廈訓評 2020</font></p>
      </div>
      </footer>
    </div>   
  </div>
</body>
</html>
<?php
  $db_link->close();
?>