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
		<title>Image files management</title>
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
				background-color: #a9a9a9;
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
				background-color: #CCC;
			}
			.ss>img{
				height: 250px;
			}
			td.tit{
				font-size: 5px;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}
		</style>
	</head>

	<script>
		$(document).ready(function(){
			$("#CheckAll").click(function(){
				//selectall is true
				if($("#CheckAll").prop("checked")){
					//all checked square's property be true
					$("input[name='rtxts[]']").prop("checked",true);
				}else{
					//all checked square's property be false
					$("input[name='rtxts[]']").prop("checked",false);
				}
			})
		})
	</script>

	<body>
		<div class="container-fluid">
			<div class="row" align="center">
				<h2><b>????????????????????????</b></h2>
				
			</div>
			<form action="" method="post">
				<div class="row" align="center">
					<div class="col-md-12">
						<table class="tables">
							<thead>
								<tr>
									<th rowspan="2">????????????</th>
									<th colspan="3">??????</th>
									<th rowspan="2">?????????</th>
									<th rowspan="2">?????????</th>
									<th rowspan="2">????????????</th>
									<th rowspan="2"></th>
								</tr>
								<tr>
									<th>????????????</th>
									<th>????????????</th>
									<th>????????????</th>
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
													'<a href="javascript:void(0)" onclick="window.open(\'img_adfix.php?id='.$r['roomID'].'\',\'\',\'width=800,height=500\');">[??????]</a>'.
												'</div>'.
											'</td>'.
										'<tr>';
									}
								 ?>
							</tbody>
						</table>
						<hr>
						<h2><b>??????????????????????????????</b></h2>
						
						<input type="button" class="btn btn-warning btn-lg" value="??????????????????" onclick="window.open('../imgscan.php','??????????????????','_blank','resizable,height=260,width=370')">
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
						<h2><b>??????????????????</b></h2>
						
						<input type="hidden" name="action" value="delete">
						<input type="submit" name="btn" class="btn btn-warning btn-lg" value="????????????">
						<br><br>
						<table>
							<tr>
								<th><input type="checkbox" id="CheckAll"></th>
								<th>??????</th>
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
								            		'<a href="javascript:void(0)" onclick="window.open(\'rule_fix.php?id='.$l['ruleID'].'\',\'\',\'width=800,height=400\');">[??????]</a>'.
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
