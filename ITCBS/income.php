<?php
	include("sessionGet.php");
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	//session_start();
	$user = new functionalityManager;
	$listOfExpenses = $user->retrieveListOfExpenses();
	$count_ex = $user->countExpenses();
	//if(!isset($_SESSION['username'])){
	//	header('Location: index.php');
	//}
	
	
	
	for($i=0;$i<$count_ex;$i++){
		$index = "x".$i;
		if(isset($_POST[$index])){
			$index2 = "date".$i;
			$values=$user->getExpenses($_POST[$index2]);
			$date_acc = $values['DATE_ACCUMULATED'];
			$stmt = "DELETE FROM expense_log where date_accumulated = '$date_acc';";
			$result = mysqli_query($con,$stmt);
		}
	}
	if(isset($_POST['saveInput'])){
		if(isset($_POST['expenses'])){
			$expenses=$_POST['expenses'];
		}
		if(isset($_POST['expenseDetails'])){
			$details=$_POST['expenseDetails'];
		}
		$addstmt = "INSERT INTO expense_log(date_accumulated,details,amount,section)
		VALUES(timestamp(now()),'$details',$expenses,'$sessionUserSection');";
		$result = mysqli_query($con,$addstmt);
		echo mysqli_error($con);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="js/jscharts.js"></script>
		<title>ITCBS - Income</title>
		<script type="text/javascript">
			function enable(){
				document.getElementById('expenses').disabled=false;
				document.getElementById('buttonAdd').style.display='none';
				document.getElementById('buttonOK').style.display='block';
				document.getElementById('expenses').required=true;
				document.getElementById('expenseDetails').required=true;
			}
			function disable(){
				document.getElementById('expenses').disabled=true;
				document.getElementById('buttonOK').style.display='none';
				document.getElementById('buttonAdd').style.display='block';
				document.getElementById('expenses').required=false;
				document.getElementById('expenseDetails').required=false;
			}
			function checkIfNull(){
				var a = document.getElementById('expenses').value;
				if(a=="")
					return false
				else{	
					return true;
				}
			}
		</script>
	</head>
	<body>
		
		<br/>
		<?php
		//	include("account_info.php");
		?>
		<div id="income_stats">
			<form action="" method="post" onsubmit="return checkIfNull()">
				<br/>
				<fieldset>
				<legend>Income Computation</legend>
					<table>
						<col width="130px">
						<tr>
							<?php
								$stmt = "SELECT sum(total_amount) FROM soa where payment_status like 'Paid';";
								$result = mysqli_query($con,$stmt);
								$a = mysqli_fetch_array($result);
								$totalIncome = $a[0];
							?>
							
							<td class="highlight"><label for="total">Total Income</label></td>
							<?php echo '<td><input type="text" name="total" disabled id="total" value="'.$totalIncome.'" style="text-align:center; background-color:white;"/></td>';?>
							<td></td>
						</tr>
						<br/>
						<tr>
							<td class="highlight"><label for="expenses">Expenses</label></td>
							<td><input type="number" name="expenses" disabled id="expenses" value="" style="text-align:center; background-color:white;"/></td>
							<td>
								<?php 
									if($sessionUserType=="Manager"){
										echo '<div id="buttonAdd">';
										echo '<input type="button" name="expenseInput" placeholder="Amount" id="expenseInput" value="Add Expenses" style="text-align:center;" onclick="enable()"/>';
										echo '</div>';
										echo '</table><table id="buttonOK" style="display:none;"><col width="135px">';
											echo '<tr>';
												echo '<td></td>';
												echo '<td>';
													echo '<input type="text" name="expenseDetails" placeholder="Details.." id="expenseDetails" style="text-align:center; background-color:white;"/>';
													echo "<input type='submit' name='saveInput' id='saveInput' value='OK' style='text-align:center;'/>";
													echo '<input type="button" name="cancel" id="cancel" value="Cancel" style="text-align:center;" onclick="disable()"/>';
												echo '</td>';
											echo '</tr>';
										echo '</table>';
									}
								?>
							</td>
						</tr>
					</form>
					</table>
					<table>
						<col width="130px">
						<tr>
							<td> </td>
							<td><hr style="width: 150px;"></td>
							
						</tr>
						<tr>
							<td></td>
							<td>Expense List</td>
							
						</tr>
						<tr>
							<td> </td>
							<td><hr style="width: 150px;"></td>
							
						</tr>
					</table>
					<div style="height:100px;  overflow:auto;">
					<table>
						<col width="130px">
						<col width="150px">
						<?php
							$totalExpense=0;
							$sectionExpense=0;
							if($sessionUserType!="Executive")
								$stmt = "SELECT amount, details, date_accumulated from expense_log where section='$sessionUserSection';";
							else $stmt = "SELECT amount, details, date_accumulated from expense_log;";
							$result = mysqli_query($con,$stmt);
							
							$stmt2 = "SELECT sum(amount) from expense_log where section='$sessionUserSection';";
							$result2 = mysqli_query($con,$stmt2);
							$b = mysqli_fetch_array($result2);
							
							$stmt3 = "SELECT sum(amount) from expense_log;";
							$result3 = mysqli_query($con,$stmt3);
							$c = mysqli_fetch_array($result3);
							
							$sectionExpense = $b[0];
							$totalExpense = $c[0];
							
							$i=0;
							while($row=mysqli_fetch_assoc($result)){
								
								echo '<tr>';
									echo '<td></td>';
									echo '<td>'.$row['details'].'</td>';
									echo '<td>'.$row['amount'].'</td>';
									if($sessionUserType!="Executive")
										echo '<td><form action="" method="post"><input type="submit" value="-" name="x'.$i.'"/></td>';
									echo '<input type="hidden" name="date'.$i.'" value="'.$row['date_accumulated'].'"></form>';
								echo '</tr>';
								$i++;
							}
						echo '</table>';
					echo '</div>';
						echo '<table>';
							echo '<col width="130px">';
							echo '<tr>';
								echo '<td> </td>';
								echo '<td><hr style="width: 150px;"></td>';
								
							echo '</tr>';
							if($sessionUserType!="Executive"){
								echo '<tr>';
									echo '<td></td>';
									echo '<td>Section Expenses:</td>';
									echo '<td>'.$sectionExpense.'</td>';
								echo '</tr>';
							}
							echo '<tr>';
								echo '<td></td>';
								echo '<td>Overall Expenses:</td>';
								echo '<td>'.$totalExpense.'</td>';
							echo '</tr>';
							if($sessionUserType!="Executive"){
								echo '<tr>';
									$stmt = "SELECT sum(amount) from job_request where section='$sessionUserSection' and payment_status='Paid';";
									if($sessionUserSection=="Rent to Own")
										$stmt = "SELECT sum(monthly_payment) from rent_to_own_monthly where payment_status='Paid';";
									
									$result = mysqli_query($con,$stmt);
									$a = mysqli_fetch_array($result);
									
									if($sessionUserSection=="Rent to Own")
										$sectionIncome = (($a[0]/1.12)*.12);
									else $sectionIncome = $a[0];
									
									echo '<td class="highlight"><label for="net"><b>Section Net</b></label></td>';
									
									$sectionNet = $sectionIncome - $sectionExpense;
									echo '<td><input type="text" name="net" disabled id="net" value="'.$sectionNet.'" style="text-align:center; background-color:white;" /></td>';
									
									echo '<td></td>';
								echo '</tr>';
							}
						?>
						<tr>
							<td class="highlight"><label for="net"><b>Net Income</b></label></td>
							<?php
							$netIncome = $totalIncome - $totalExpense;
							echo '<td><input type="text" name="net" disabled id="net" value="'.$netIncome.'" style="text-align:center; background-color:white;" /></td>';
							?>
							<td></td>
						</tr>
					</table>
					<br/>
				</fieldset>
			</div>
			
		
		
		<div id="incomePerSect">
			<div id="incomeNetAd">
				<?php
					$stmt = "SELECT count(*) as num, sum(amount) as total from job_request where section='Network Ad' and payment_status='Paid';";
					$result = mysqli_query($con,$stmt);
					$row=mysqli_fetch_assoc($result);
					echo '<fieldset>';
					echo '<legend>Network Ad Income</legend>';
					echo '<table style="width:100%;">';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ".$row['num'];
							echo '</td>';
							echo '<td>';
								echo "Amount: ".$row['total'];
							echo '</td>';
						echo '</tr>';
					echo '</table>';
					echo '</fieldset>';
				?>
			</div>
			<br/>
			<div id="incomeSysAd">
				<?php
					$stmt = "SELECT count(*) as num, sum(amount) as total from job_request where section='System Ad' and payment_status='Paid';";
					$result = mysqli_query($con,$stmt);
					$row=mysqli_fetch_assoc($result);
					echo '<fieldset>';
					echo '<legend>System Ad Income</legend>';
					echo '<table style="width:100%;">';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ".$row['num'];
							echo '</td>';
							echo '<td>';
								echo "Amount: ".$row['total'];
							echo '</td>';
						echo '</tr>';
					echo '</table>';
					echo '</fieldset>';
				?>
			</div>
			<br/>
			<div id="incomeRto">
				<?php
					$stmt = "SELECT count(*) as num, sum(monthly_payment) as total from rent_to_own_monthly where payment_status='Paid';";
					$result = mysqli_query($con,$stmt);
					$row=mysqli_fetch_assoc($result);
					echo '<fieldset>';
					echo '<legend>Rent to Own Income</legend>';
					echo '<table style="width:100%;">';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ".$row['num'];
							echo '</td>';
							echo '<td>';
								echo "Amount: ".(($row['total']/1.12)*.12);
							echo '</td>';
						echo '</tr>';
					echo '</table>';
					echo '</fieldset>';
				?>
			</div>
			<br/>
			<div id="incomeMIS">
				<?php
					$stmt = "SELECT count(*) as num, sum(amount) as total from job_request where section='MIS' and payment_status='Paid';";
					$result = mysqli_query($con,$stmt);
					$row=mysqli_fetch_assoc($result);
					echo '<fieldset>';
					echo '<legend>MIS Income</legend>';
					echo '<table style="width:100%;">';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ".$row['num'];
							echo '</td>';
							echo '<td>';
								echo "Amount: ".$row['total'];
							echo '</td>';
						echo '</tr>';
					echo '</table>';
					echo '</fieldset>';
				?>
			</div>
			<br/>
			<div id="incomeTechSup">
				<?php
					$stmt = "SELECT count(*) as num, sum(amount) as total from job_request where section='Tech Support' and payment_status='Paid';";
					$result = mysqli_query($con,$stmt);
					$row=mysqli_fetch_assoc($result);
					echo '<fieldset>';
					echo '<legend>Tech Support Income</legend>';
					echo '<table style="width:100%;">';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ".$row['num'];
							echo '</td>';
							echo '<td>';
								echo "Amount: ".$row['total'];
							echo '</td>';
						echo '</tr>';
					echo '</table>';
					echo '</fieldset>';
				?>
			</div>
		</div>
		<div id="pieChart">
				<br/>
				<fieldset>
				<legend>Income Per Section</legend>
				<div id="chartid">
				<?php
					$admin = new functionalityManager;
					$tech=$admin->getAmountSection('Tech Support');
					if($tech=="") $tech=0;
					$system=$admin->getAmountSection('System Ad');
					if($system=="") $system=0;
					$network=$admin->getAmountSection('Network Ad');
					if($network=="") $network=0;
					$mis=$admin->getAmountSection('MIS');
					if($mis=="") $mis=0;
					//echo $mis;
					$rent=$admin->getAmountSection('Rent to Own');
					if($rent=="") $rent=0;
				?>
					<script type="text/javascript">
						var tech = <?php echo $tech;?>;
						var system = <?php echo $system;?>;
						var network = <?php echo $network;?>;
						var mis = <?php echo $mis;?>;
						var rent = <?php echo $rent;?>;
						var myData = new Array(["Tech Support", tech], ["System Ad", system], ["Network Ad", network], ["MIS", mis], ["Rent to Own",rent]);
						var colors = ['#FACC00', '#FB9900', '#FB6600', '#FB4800', '#CB0A0A'];
						var myChart = new JSChart('chartid', 'pie');
						myChart.setSize(500, 300);
						myChart.setTitle("Income per Section");
						myChart.setTitleFontFamily('Times New Roman');
						myChart.setTitleFontSize(14);
						myChart.setDataArray(myData);
						myChart.colorize(['#99CDFB','#3366FB','#0000FA','#F8CC00','#F89900']);
						myChart.setTitleColor('#857D7D');
						myChart.setPieUnitsColor('#9B9B9B');
						myChart.setPieValuesColor('#6A0000');
						myChart.setPiePosition(180, 165);
						myChart.setShowXValues(false);
						myChart.setLegend('#99CDFB', 'Tech Support');
						myChart.setLegend('#3366FB', 'System Ad');
						myChart.setLegend('#0000FA', 'Network Ad');
						myChart.setLegend('#F8CC00', 'MIS');
						myChart.setLegend('#F89900', 'Rent to Own');
						myChart.setLegendShow(true);
						myChart.setLegendPosition(350, 120);
						myChart.setPieAngle(30);
						myChart.set3D(true);
						myChart.draw();
					</script>
				</div>
		</div>
	</body>
</html>