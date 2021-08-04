<?php
ini_set('display_errors', 'off');
session_start();
require_once("connMysqlObj.php");
date_default_timezone_set('Asia/Taipei');//設定台北時區
$time=date("Y-m-d H:i:s");
//check login or not then redirect to corrrespond page
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
  //level member redirect to member center
  if($_SESSION["memberLevel"]=="member"){
    header("Location: member_center.php");
  //or to admin page
  }else{
    header("Location: member_admin.php"); 
  }
}
//query login
if(isset($_POST["userNO"]) && isset($_POST["passwd"])){
  //connect member data
  $query_RecLogin = "SELECT userNO, m_passwd, m_level FROM memberdata WHERE userNO=?";
  $stmt=$db_link->prepare($query_RecLogin);
  $stmt->bind_param("s", $_POST["userNO"]);
  $stmt->execute();
  //get passwd
  $stmt->bind_result($userNO, $passwd, $level); 
  $stmt->fetch();
  $stmt->close();
  //compare passwd
  if(password_verify($_POST["passwd"],$passwd)){
    //count login times
    $query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime='$time' WHERE userNO=?";
    $stmt=$db_link->prepare($query_RecLoginUpdate);
      $stmt->bind_param("s", $userNO);
      $stmt->execute(); 
      $stmt->close();
    //setup member level
    $_SESSION["loginMember"]=$userNO;
    $_SESSION["memberLevel"]=$level;
    //setcookie
    if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
      setcookie("remUser", $_POST["userNO"], time()+365*24*60);
      setcookie("remPass", $_POST["passwd"], time()+365*24*60);
    }else{
      if(isset($_COOKIE["remUser"])){
        setcookie("remUser", $_POST["userNO"], time()-100);
        setcookie("remPass", $_POST["passwd"], time()-100);
      }
    }
    //level member to member center
    if($_SESSION["memberLevel"]=="member"){
      header("Location: member_center.php");
    //or admin page
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
<title>member login</title>
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
body{ font-size: 18px;}
.footer{
    font-size:20px;
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
    </div>
  </nav>
  <div class="container"> 
    <div class="col-xs-12 col-md-offset-4 col-md-4" style="background-color: #BEBEBE"> 
          <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
      <h2 align="center"><b>會員登入</b></h2><hr>
      <div class="row">
      <form name="form1" method="post" action="">
        <p class="col-xs-12 col-sm-12 col-md-12" align="center">
        <label for="userNO" class="col-xs-4 col-sm-offset-3 col-sm-2 col-md-offset-0 col-md-4" style="margin-top: 4px;"><font size="4px">帳號:</font></label>
            <input name="userNO" type="text" id="userNO" class="col-xs-7 col-sm-3 col-md-7" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">          
        </p>
        <p class="col-xs-12 col-sm-12 col-md-12" align="center">
          <label for="passwd" class="col-xs-4 col-sm-offset-3 col-sm-2 col-md-offset-0 col-md-4" style="margin-top: 4px;"><font size="4px">密碼:</font></label>
            <input  name="passwd" type="password" class="col-xs-7 col-sm-3 col-md-7" id="passwd" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">          
        <p>
        <div class="col-xs-12 col-sm-12 col-md-12" align="center">
          <input name="rememberme" type="checkbox" id="rememberme" value="true" checked>記住我的帳號密碼<br><br>
          <input type="button" value="忘記密碼" onclick="location.href='admin_passmail.php'">
          <input type="submit" name="button" id="button" value="登入系統">
        </div>
      </form>
      </div>
      <hr>
        <div align="center">        
          <a href="memberjoin.php">快速註冊</a>&nbsp;&nbsp;
          <a href="../index.php">回首頁</a>
        </div>
        <br>
      </div> 
    <div class="col-md-12" style="margin-top: 5%"><!--footer-->
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