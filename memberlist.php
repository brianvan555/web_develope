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
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
$stmt=$db_link->prepare($query_delMember);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$stmt->close();
//重新導向回到主畫面
header("Location: memberlist.php");
}
//選取所有一般會員資料
//預設每頁筆數
$pageRow_records = 10;
//預設頁數
$num_pages = 1;
if (isset($_GET['page'])) {
$num_pages = $_GET['page'];
}
$startRow_records = ($num_pages -1) * $pageRow_records;
$query_RecMember = "SELECT * FROM memberdata ORDER BY m_level ";
$query_limit_RecMember = $query_RecMember." LIMIT {$startRow_records}, {$pageRow_records}";
$RecMember = $db_link->query($query_limit_RecMember);
$all_RecMember = $db_link->query($query_RecMember);
$total_records = $all_RecMember->num_rows;
$total_pages = ceil($total_records/$pageRow_records);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<title>華廈訓評會員列表</title>
</head>
<script language="javascript">
    function deletesure(){
    if (confirm('\n您確定要刪除這個會員嗎?\n刪除後無法恢復!\n')) return true;
    return false;}    
</script>
<body>
	<div class="col-md-12" align="center" >
      <div class="row">
        <h2>會員資料列表</h2>
        <hr>
      </div>
      <div class="col-md-12">
        <?php
        echo '<table class="table table-striped table-hover"';
        echo '<tr><th>姓名</th><th>身分證/護照號碼</th><th>會員等級</th><th>會員信箱</th><th>會員手機</th><th>修改</th><th>刪除</th></tr>';
          while($row_RecMember=$RecMember->fetch_assoc()){
          echo "<tr>";
            echo '<td>'.$row_RecMember["m_name"].'</td><td>'.$row_RecMember["userNO"].'</td><td>'.$row_RecMember["m_level"].'</td><td>'.$row_RecMember["uMail"].'</td><td>'.$row_RecMember["phone"].'</td>';
            echo '<td><a href="member_adminupdate.php?id='.$row_RecMember["m_id"].'"><button class="btn btn-success" style="height:35px;">修改</button></a></td>';
            echo '<td><a href="?action=delete&id='.$row_RecMember["m_id"].'"onClick="return deletesure();"><button class="btn btn-danger" style="height:35px;">刪除</button></a>';//本頁執行表單動作,不須創造表單
            echo '</td>';
          echo "</tr>";
          } echo '</table>';
        ?>
        <hr><br>
        <table>
          <tr>
            <td align="left"><p>資料筆數：<?php echo $total_records;?> | </p></td>            
            <td align="center"><p>
              <?php if ($num_pages > 1) { // 若不是第一頁則顯示 ?>
              <a href="?page=1">第一頁</a> | <a href="?page=<?php echo $num_pages-1;?>">上一頁</a>
              <?php }?>
              <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 ?>
              <a href="?page=<?php echo $num_pages+1;?>">下一頁</a> | <a href="?page=<?php echo $total_pages;?>">最末頁</a>
              <?php }?> | <a href="member_join.php">新增會員</a>
            </p></td>            
          </tr>
        </table><br><br>
      </div>     
    </div>
</body>
</html>
<?php
$db_link->close();
?>