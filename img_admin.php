<?php 
	session_start();
	require_once("connMysqlObj.php");
	$rquery = "SELECT * FROM availablerooms ORDER BY roomID ASC";
	$rqr = $db_link->query($rquery);
	$sqr = $db_link->query($rquery);

	$lquery = "SELECT * FROM rules ORDER BY ruleID";
	$lqr = $db_link->query($lquery);

	if(isset($_POST['action'])&&($_POST['action']=="delete")){
		$dar = $_POST['rtxts'];
		$cd = count($dar);
		for($i=0;$i<$cd;$i++){
			$dr = "DELETE FROM rules WHERE ruleID='$dar[$i]'";
			$dl = $db_link->query($dr);
		}
		header("Location: img_admin.php");
	}
 ?>
<html>
	<head>
		<title>教室資訊圖檔管理</title>
		<meta charset="utf-8">
		<!-- Latest compiled and minified CSS & JS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="//code.jquery.com/jquery.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<style>
			table,th,td{
				border-collapse: collapse;
				border: 1px solid black;
				text-align: center;
				vertical-align: middle;
				font-size: 15px;
				table-layout: fixed;
			}
			th{
				background-color: #CCC;
				padding: 6px;
				white-space: nowrap;
			}
			td{
				text-align: center;
				background-color: #FFF;
				padding: 6px;
			}
			td.op{
				white-space: nowrap;
			}
			tr.stripe > td{
				background-color: #FFFF09;
			}
			.ss>img{
				height: 250px;
			}
			td.tit{
				font-size: 5px;
				/*禁止td換行*/
				white-space: nowrap;
				/*隱藏X,Y滾動條*/
				overflow: hidden;
				/*將顯示不完的以...顯示*/
				text-overflow: ellipsis;
			}
		</style>
	</head>

	<script>
		$(document).ready(function(){
			$("#CheckAll").click(function(){
				//如果全選按鈕有被選擇的話（被選擇是true）
				if($("#CheckAll").prop("checked")){
					//把所有的核取方框的property都變成勾選
					$("input[name='rtxts[]']").prop("checked",true);
				}else{
					//把所有的核取方框的property都取消勾選
					$("input[name='rtxts[]']").prop("checked",false);
				}
			})
		})
	</script>

	<body>
		<div class="container-fluid">
			<div class="row" align="center">
				<h2>教室資訊圖檔管理</h2>
				<hr>
			</div>
			<form action="" method="post">
				<div class="row" align="center">
					<div class="col-md-12">
						<table class="tables">
							<thead>
								<tr>
									<th rowspan="2">教室名稱</th>
									<th colspan="3">價格</th>
									<th rowspan="2">排列式</th>
									<th rowspan="2">分組式</th>
									<th rowspan="2">圖檔路徑</th>
									<th rowspan="2"></th>
								</tr>
								<tr>
									<th>單一時段</th>
									<th>兩個時段</th>
									<th>三個時段</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$count = 0;
									while($r=mysqli_fetch_assoc($rqr)){
										$count++;
										if($count%2==0){
											echo '<tr class="stripe">';
										}else{
											echo '<tr>';
										}

										echo
											'<td>'.$r['room'].'</td>'.
											'<td>'.$r['onesect'].'</td>'.
											'<td>'.$r['tusects'].'</td>'.
											'<td>'.$r['thrusects'].'</td>'.
											'<td>'.$r['area'].'</td>'.
											'<td>'.$r['cont'].'</td>'.
											'<td class="tit" title="'.$r['pic'].'">'.$r['pic'].'</td>'.
											'<td class="op">'.
												'<div class="actiondiv">'.
													'<a href="javascript:void(0)" onclick="window.open(\'img_adfix.php?id='.$r['roomID'].'\',\'\',\'width=800,height=500\');">[修改]</a>'.
												'</div>'.
											'</td>'.
										'<tr>';
									}
								 ?>
							</tbody>
						</table>
						<hr>
						<h2>各教室跑馬燈所選圖檔</h2>
						<hr>
						<input type="button" value="管理站內圖檔" onclick="window.open('../imgscan.php','管理站內圖檔','_blank','resizable,height=260,width=370')">
					</div>

					<div class="col-md-12 ss">
						<?php 
							while($s=mysqli_fetch_assoc($sqr)){
								$p = explode(",",$s['pic']);
								echo '<h3>'.$s['room'].'</h3>';
								for($i=0;$i<count($p);$i++){
									echo '<img src="../'.$p[$i].'" alt="../'.$p[$i].'" title="'.$p[$i].'">&emsp;';
								}
							}
						 ?>
						<hr>
						<h2>租借規則管理</h2>
						<hr>
						<input type="hidden" name="action" value="delete">
						<input type="submit" name="btn" value="確認刪除">
						<br><br>
						<table>
							<tr>
								<th><input type="checkbox" id="CheckAll"></th>
								<th>內文</th>
								<th></th>
							</tr>
							<?php 
								$cnt = 0;
								while($l=mysqli_fetch_assoc($lqr)){
									$cnt++;
									if($cnt%2==0){
										echo '<tr class="stripe">';
									}else{
										echo '<tr>';
									}
									echo    '<td><input type="checkbox" name="rtxts[]" value="'.$l['ruleID'].'"></td>'.
											'<td style="text-align:left;">'.$l['ruletxts'].'</td>'.
											'<td>'.
												'<div class="actiondiv">'.
								            		'<a href="javascript:void(0)" onclick="window.open(\'rule_fix.php?id='.$l['ruleID'].'\',\'\',\'width=800,height=400\');">[修改]</a>'.
								            	'</div>'.
								            '</td>'.
										'</tr>';
								}
							 ?>
						</table>
					</div>
				</div>
			</form>
		</div>
	</body>
	<?php 
		$db_link->close();
		mysqli_free_result($rqr);
		mysqli_free_result($sqr);
	 ?>
</html>