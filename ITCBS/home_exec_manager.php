<?php
	include("sessionGet.php");
	//session_start();
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="js/jscharts.js"></script>
<title>.::ITC Billing System::.</title>
</head>

<body>
	<div id="justSpace">
		<br/>
	</div>
	<div id ="MainContainer">
		<div id="dashboardLeft">
			<div id="incomeHome">
				<form action="" method="post" onsubmit="return checkIfNull()">
					<fieldset>
					<legend><a href="income.php">INCOME</a></legend>
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
							
						</form>
						</table>
						<table>
							<?php
								$totalExpense=0;
								$stmt = "SELECT sum(amount) as amount from expense_log;";
								$result = mysqli_query($con,$stmt);
								$i=0;
								while($row=mysqli_fetch_assoc($result)){
									$totalExpense = $row['amount'];
								}
							
								echo '<tr>';
									echo '<td class="highlight">Total Expenses:</td>';
									echo '<td><input type="text" name="net" disabled id="net" value="'.$totalExpense.'" style="text-align:center; background-color:white;" /></td>';
								echo '</tr>';
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
						<table>
						<?php
						$stmt = "SELECT sum(amount) as total from job_request where section='Network Ad' and payment_status='Paid';";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
							echo '<tr>';
								echo '<td class="highlight">';
									echo "Network Ad ";
								echo '</td>';	
								$income=$row['total'];
								echo '<td><input type="text" name="total" disabled id="total" value="'.$income.'" style="text-align:center; background-color:white;"/></td>';
							echo '</tr>';
						$stmt = "SELECT sum(amount) as total from job_request where section='System Ad' and payment_status='Paid';";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
							echo '<tr>';
								echo '<td class="highlight">';
									echo "System Ad ";
								echo '</td>';	
								$income=$row['total'];
								echo '<td><input type="text" name="total" disabled id="total" value="'.$income.'" style="text-align:center; background-color:white;"/></td>';
							echo '</tr>';
						$stmt = "SELECT sum(monthly_payment) as total from rent_to_own_monthly where payment_status='Paid';";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
							echo '<tr>';
								echo '<td class="highlight">';
									echo "Rent to Own";
								echo '</td>';	
								$income=($row['total']/1.12)*.12;
								echo '<td><input type="text" name="total" disabled id="total" value="'.$income.'" style="text-align:center; background-color:white;"/></td>';
							echo '</tr>';
						$stmt = "SELECT sum(amount) as total from job_request where section='MIS' and payment_status='Paid';";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
							echo '<tr>';
								echo '<td class="highlight">';
									echo "MIS";
								echo '</td>';	
								$income=$row['total'];
								echo '<td><input type="text" name="total" disabled id="total" value="'.$income.'" style="text-align:center; background-color:white;"/></td>';
							echo '</tr>';
						$stmt = "SELECT sum(amount) as total from job_request where section='Tech Support' and payment_status='Paid';";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
							echo '<tr>';
								echo '<td class="highlight">';
									echo "Tech Support";
								echo '</td>';	
								$income=$row['total'];
								echo '<td><input type="text" name="total" disabled id="total" value="'.$income.'" style="text-align:center; background-color:white;"/></td>';
							echo '</tr>';
						echo '</table>';
						echo '<br/>';
						
					?>
						
						
						
					</fieldset>
					
				
			</div>
		
			<div id="jrDetails">
			<br/>
			<fieldset>
			<legend>
			<?php
				if($sessionUserType=="Manager")
					echo '<a href="job_request_manager.php">';
				else echo '<a href="job_request_exec.php">';
				echo 'Job Requests</a>';
			?>
			</legend>
				<?php
					$stmt = "SELECT count(*) FROM job_request";
					$result = mysqli_query($con,$stmt);
					$a=mysqli_fetch_array($result);
					echo '<table class="jrDetalye">';
						echo '<col width=130>';
							echo '<tr>';
								echo '<td>';
									echo "Quantity: ";
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT count(*) FROM job_request";

									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "In Process: ";
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT count(*) FROM job_request
										where status='In Process'";

									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Pending: ";
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT count(*) FROM job_request
										where status='Pending'";

									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Done: ";
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT count(*) FROM job_request
										where status='Done'";

									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Cancelled: ";
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT count(*) FROM job_request
										where status='Cancelled'";

									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td><hr/></td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Unpaid: ";
								echo '</td>';
								echo '<td>';
									
									$stmt = "SELECT count(*) FROM job_request
									where status='Done' and payment_status='Unpaid'";
									
									
									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Paid: ";
								echo '</td>';
								echo '<td>';
									
									$stmt = "SELECT count(*) FROM job_request
									where status='Done' and payment_status='Paid'";
									
									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo $a[0];
								echo '</td>';
							echo '</tr>';
						echo '</table>';
				?>
			</fieldset>
			</div>
		</div>
		<div id="dashboardRight">
			<div id="chartIncome">
				<br/>
				<fieldset>
				<legend><a href="income.php">Income Per Month</a></legend>
				<div id="graphA">Loading graph...</div>
				
				<?php
					$admin = new functionalityManager;
					$janR=$admin->getAmountPerSection('January', 'Rent to Own');
					if($janR=="") $janR=0;
					$febR=$admin->getAmountPerSection('February', 'Rent to Own');
					if($febR=="") $febR=0;
					$marchR=$admin->getAmountPerSection('March', 'Rent to Own');
					if($marchR=="") $marchR=0;
					$aprilR=$admin->getAmountPerSection('April', 'Rent to Own');
					if($aprilR=="") $aprilR=0;
					$mayR=$admin->getAmountPerSection('May', 'Rent to Own');
					if($mayR=="") $mayR=0;
					$juneR=$admin->getAmountPerSection('June', 'Rent to Own');
					if($juneR=="") $juneR=0;
					$julyR=$admin->getAmountPerSection('July', 'Rent to Own');
					if($julyR=="") $julyR=0;
					$augR=$admin->getAmountPerSection('August', 'Rent to Own');
					if($augR=="") $augR=0;
					$septR=$admin->getAmountPerSection('September', 'Rent to Own');
					if($septR=="") $septR=0;
					$octR=$admin->getAmountPerSection('October', 'Rent to Own');
					if($octR=="") $octR=0;
					$novR=$admin->getAmountPerSection('November', 'Rent to Own');
					if($novR=="") $novR=0;
					$decR=$admin->getAmountPerSection('December', 'Rent to Own');
					if($decR=="") $decR=0;
					
						//TECH
					$janT=$admin->getAmountPerSection('January', 'Tech Support');
					if($janT=="") $janT=0;
					$febT=$admin->getAmountPerSection('February', 'Tech Support');
					if($febT=="") $febT=0;
					$marchT=$admin->getAmountPerSection('March', 'Tech Support');
					if($marchT=="") $marchT=0;
					$aprilT=$admin->getAmountPerSection('April', 'Tech Support');
					if($aprilT=="") $aprilT=0;
					$mayT=$admin->getAmountPerSection('May', 'Tech Support');
					if($mayT=="") $mayT=0;
					$juneT=$admin->getAmountPerSection('June', 'Tech Support');
					if($juneT=="") $juneT=0;
					$julyT=$admin->getAmountPerSection('July', 'Tech Support');
					if($julyT=="") $julyT=0;
					$augT=$admin->getAmountPerSection('August', 'Tech Support');
					if($augT=="") $augT=0;
					$septT=$admin->getAmountPerSection('September', 'Tech Support');
					if($septT=="") $septT=0;
					$octT=$admin->getAmountPerSection('October', 'Tech Support');
					if($octT=="") $octT=0;
					$novT=$admin->getAmountPerSection('November', 'Tech Support');
					if($novT=="") $novT=0;
					$decT=$admin->getAmountPerSection('December', 'Tech Support');
					if($decT=="") $decT=0;
					
					//MIS
					$janM=$admin->getAmountPerSection('January', 'MIS');
					if($janM=="") $janM=0;
					$febM=$admin->getAmountPerSection('February', 'MIS');
					if($febM=="") $febM=0;
					$marchM=$admin->getAmountPerSection('March', 'MIS');
					if($marchM=="") $marchM=0;
					$aprilM=$admin->getAmountPerSection('April', 'MIS');
					if($aprilM=="") $aprilM=0;
					$mayM=$admin->getAmountPerSection('May', 'MIS');
					if($mayM=="") $mayM=0;
					$juneM=$admin->getAmountPerSection('June', 'MIS');
					if($juneM=="") $juneM=0;
					$julyM=$admin->getAmountPerSection('July', 'MIS');
					if($julyM=="") $julyM=0;
					$augM=$admin->getAmountPerSection('August', 'MIS');
					if($augM=="") $augM=0;
					$septM=$admin->getAmountPerSection('September', 'MIS');
					if($septM=="") $septM=0;
					$octM=$admin->getAmountPerSection('October', 'MIS');
					if($octM=="") $octM=0;
					$novM=$admin->getAmountPerSection('November', 'MIS');
					if($novM=="") $novM=0;
					$decM=$admin->getAmountPerSection('December', 'MIS');
					if($decM=="") $decM=0;
					
					//SYSAD
					$janS=$admin->getAmountPerSection('January', 'System Ad');
					if($janS=="") $janS=0;
					$febS=$admin->getAmountPerSection('February', 'System Ad');
					if($febS=="") $febS=0;
					$marchS=$admin->getAmountPerSection('March', 'System Ad');
					if($marchS=="") $marchS=0;
					$aprilS=$admin->getAmountPerSection('April', 'System Ad');
					if($aprilS=="") $aprilS=0;
					$mayS=$admin->getAmountPerSection('May', 'System Ad');
					if($mayS=="") $mayS=0;
					$juneS=$admin->getAmountPerSection('June', 'System Ad');
					if($juneS=="") $juneS=0;
					$julyS=$admin->getAmountPerSection('July', 'System Ad');
					if($julyS=="") $julyS=0;
					$augS=$admin->getAmountPerSection('August', 'System Ad');
					if($augS=="") $augS=0;
					$septS=$admin->getAmountPerSection('September', 'System Ad');
					if($septS=="") $septS=0;
					$octS=$admin->getAmountPerSection('October', 'System Ad');
					if($octS=="") $octS=0;
					$novS=$admin->getAmountPerSection('November', 'System Ad');
					if($novS=="") $novS=0;
					$decS=$admin->getAmountPerSection('December', 'System Ad');
					if($decS=="") $decS=0;
					
					//NETAD
					$janN=$admin->getAmountPerSection('January', 'Network Ad');
					if($janN=="") $janN=0;
					$febN=$admin->getAmountPerSection('February', 'Network Ad');
					if($febN=="") $febN=0;
					$marchN=$admin->getAmountPerSection('March', 'Network Ad');
					if($marchN=="") $marchN=0;
					$aprilN=$admin->getAmountPerSection('April', 'Network Ad');
					if($aprilN=="") $aprilN=0;
					$mayN=$admin->getAmountPerSection('May', 'Network Ad');
					if($mayN=="") $mayN=0;
					$juneN=$admin->getAmountPerSection('June', 'Network Ad');
					if($juneN=="") $juneN=0;
					$julyN=$admin->getAmountPerSection('July', 'Network Ad');
					if($julyN=="") $julyN=0;
					$augN=$admin->getAmountPerSection('August', 'Network Ad');
					if($augN=="") $augN=0;
					$septN=$admin->getAmountPerSection('September', 'Network Ad');
					if($septN=="") $septN=0;
					$octN=$admin->getAmountPerSection('October', 'Network Ad');
					if($octN=="") $octN=0;
					$novN=$admin->getAmountPerSection('November', 'Network Ad');
					if($novN=="") $novN=0;
					$decN=$admin->getAmountPerSection('December', 'Network Ad');
					if($decN=="") $decN=0;
					?>
					<script type="text/javascript">
						var janR = <?php echo $janR?>;
						var febR = <?php echo $febR?>;
						var marchR = <?php echo $marchR?>;
						var aprilR = <?php echo $aprilR?>;
						var mayR = <?php echo $mayR?>;
						var juneR = <?php echo $juneR?>;
						var julyR = <?php echo $julyR?>;
						var augR = <?php echo $augR?>;
						var septR = <?php echo $septR?>;
						var octR = <?php echo $octR?>;
						var novR = <?php echo $novR?>;
						var decR = <?php echo $decR?>;
						
						//TECH
						var janT = <?php echo $janT?>;
						var febT = <?php echo $febT?>;
						var marchT = <?php echo $marchT?>;
						var aprilT = <?php echo $aprilT?>;
						var mayT = <?php echo $mayT?>;
						var juneT = <?php echo $juneT?>;
						var julyT = <?php echo $julyT?>;
						var augT = <?php echo $augT?>;
						var septT = <?php echo $septT?>;
						var octT = <?php echo $octT?>;
						var novT = <?php echo $novT?>;
						var decT = <?php echo $decT?>;
						
						//SYSAD
						var janS = <?php echo $janS?>;
						var febS = <?php echo $febS?>;
						var marchS = <?php echo $marchS?>;
						var aprilS = <?php echo $aprilS?>;
						var mayS = <?php echo $mayS?>;
						var juneS = <?php echo $juneS?>;
						var julyS = <?php echo $julyS?>;
						var augS = <?php echo $augS?>;
						var septS = <?php echo $septS?>;
						var octS = <?php echo $octS?>;
						var novS = <?php echo $novS?>;
						var decS = <?php echo $decS?>;
						
						//NETAD
						var janN = <?php echo $janN?>;
						var febN = <?php echo $febN?>;
						var marchN = <?php echo $marchN?>;
						var aprilN = <?php echo $aprilN?>;
						var mayN = <?php echo $mayN?>;
						var juneN = <?php echo $juneN?>;
						var julyN = <?php echo $julyN?>;
						var augN = <?php echo $augN?>;
						var septN = <?php echo $septN?>;
						var octN = <?php echo $octN?>;
						var novN = <?php echo $novN?>;
						var decN = <?php echo $decN?>;
						
						//MIS
						var janM = <?php echo $janM?>;
						var febM = <?php echo $febM?>;
						var marchM = <?php echo $marchM?>;
						var aprilM = <?php echo $aprilM?>;
						var mayM = <?php echo $mayM?>;
						var juneM = <?php echo $juneM?>;
						var julyM = <?php echo $julyM?>;
						var augM = <?php echo $augM?>;
						var septM = <?php echo $septM?>;
						var octM = <?php echo $octM?>;
						var novM = <?php echo $novM?>;
						var decM = <?php echo $decM?>;
						
						var myChart = new JSChart('graphA', 'line');
						myChart.setDataArray([["",0],["Jan", janT], ["Feb", febT], ["March", marchT], ["April", aprilT], ["May", mayT], ["June", juneT], ["July", julyT], ["Aug", augT], ["Sept", septT], ["Oct", octT], ["Nov", novT], ["Dec", decT]],"tech");
						myChart.setDataArray([["",0],["Jan", janS], ["Feb", febS], ["March", marchS], ["April", aprilS], ["May", mayS], ["June", juneS], ["July", julyS], ["Aug", augS], ["Sept", septS], ["Oct", octS], ["Nov", novS], ["Dec", decS]],"sysad");
						myChart.setDataArray([["",0],["Jan", janN], ["Feb", febN], ["March", marchN], ["April", aprilN], ["May", mayN], ["June", juneN], ["July", julyN], ["Aug", augN], ["Sept", septN], ["Oct", octN], ["Nov", novN], ["Dec", decN]],"netad");
						myChart.setDataArray([["",0],["Jan", janM], ["Feb", febM], ["March", marchM], ["April", aprilM], ["May", mayM], ["June", juneM], ["July", julyM], ["Aug", augM], ["Sept", septM], ["Oct", octM], ["Nov", novM], ["Dec", decM]],"mis");
						myChart.setDataArray([["",0],["Jan", janR], ["Feb", febR], ["March", marchR], ["April", aprilR], ["May", mayR], ["June", juneR], ["July", julyR], ["Aug", augR], ["Sept", septR], ["Oct", octR], ["Nov", novR], ["Dec", decR]],"rent");
						myChart.setTitle('Income/Section');
						myChart.setTitleColor('#8E8E8E');
						myChart.setTitleFontSize(11);
						myChart.setAxisNameX('');
						myChart.setAxisNameY('');
						myChart.setAxisColor('#8420CA');
						myChart.setAxisValuesColor('#949494');
						myChart.setAxisPaddingLeft(50);
						//myChart.setAxisPaddingRight(120);
						myChart.setAxisPaddingTop(50);
						myChart.setAxisPaddingBottom(40);
						myChart.setAxisValuesDecimals(2);
						myChart.setAxisValuesNumberX(1);
						myChart.setIntervalStartY(0);
						//myChart.setIntervalEndY(50000);
						//myChart.setAxisValuesNumberY(20);
						myChart.setShowXValues(false);
						myChart.setGridColor('#C5A2DE');
						myChart.setLineColor('#B13B32');
						myChart.setLineColor('#A4D314', 'sysad');
						myChart.setLineColor('#BBBBBB', 'netad');
						myChart.setLineColor('#142214', 'rent');
						myChart.setLineColor('#00BB4B', 'mis');
						myChart.setLineWidth(2);
						myChart.setFlagColor('#9D12FD');
						myChart.setFlagRadius(4);
						//myChart.setTooltip(["Jan"]);
						//myChart.setTooltip(["Feb"]);
						//myChart.setTooltip(["March"]);
						//myChart.setTooltip(["April"]);
						//myChart.setTooltip(["May"]);
						//myChart.setTooltip(["June"]);
						//myChart.setTooltip(["July"]);
						//myChart.setTooltip(["Aug"]);
						//myChart.setTooltip(["Sept"]);
						//myChart.setTooltip(["Oct"]);
						//myChart.setTooltip(["Nov"]);
						//myChart.setTooltip(["Dec"]);
						myChart.setSize(450, 200);
						myChart.setLegendForLine('tech','Tech Support');
						myChart.setLegendForLine('mis','MIS');
						myChart.setLegendForLine('sysad','System Ad');
						myChart.setLegendForLine('netad','Network Ad');
						myChart.setLegendForLine('rent','Rent to Own');
						myChart.setBackgroundImage('chart_bg.jpg');
						myChart.setLegendShow(true);
						//myChart.setLegendPosition(100,100);
						myChart.draw();
					</script>
				</fieldset>
				</div>
			<div id="chartJobRequest">
				<br/><br/>
				<fieldset>
				<legend>
			<?php
				if($sessionUserType=="Manager")
					echo '<a href="job_request_manager.php">';
				else echo '<a href="job_request_exec.php">';
				echo 'Job Request Per Month</a>';
			?>
			</legend>
				
				<div id="graph">Loading graph...</div>
				
				<?php
					$janR=$admin->getAmount('January', 'Rent to Own');
					if($janR=="") $janR=0;
					$febR=$admin->getAmount('February', 'Rent to Own');
					if($febR=="") $febR=0;
					$marchR=$admin->getAmount('March', 'Rent to Own');
					if($marchR=="") $marchR=0;
					$aprilR=$admin->getAmount('April', 'Rent to Own');
					if($aprilR=="") $aprilR=0;
					$mayR=$admin->getAmount('May', 'Rent to Own');
					if($mayR=="") $mayR=0;
					$juneR=$admin->getAmount('June', 'Rent to Own');
					if($juneR=="") $juneR=0;
					$julyR=$admin->getAmount('July', 'Rent to Own');
					if($julyR=="") $julyR=0;
					$augR=$admin->getAmount('August', 'Rent to Own');
					if($augR=="") $augR=0;
					$septR=$admin->getAmount('September', 'Rent to Own');
					if($septR=="") $septR=0;
					$octR=$admin->getAmount('October', 'Rent to Own');
					if($octR=="") $octR=0;
					$novR=$admin->getAmount('November', 'Rent to Own');
					if($novR=="") $novR=0;
					$decR=$admin->getAmount('December', 'Rent to Own');
					if($decR=="") $decR=0;
					
						//TECH
					$janT=$admin->getAmount('January', 'Tech Support');
					if($janT=="") $janT=0;
					$febT=$admin->getAmount('February', 'Tech Support');
					if($febT=="") $febT=0;
					$marchT=$admin->getAmount('March', 'Tech Support');
					if($marchT=="") $marchT=0;
					$aprilT=$admin->getAmount('April', 'Tech Support');
					if($aprilT=="") $aprilT=0;
					$mayT=$admin->getAmount('May', 'Tech Support');
					if($mayT=="") $mayT=0;
					$juneT=$admin->getAmount('June', 'Tech Support');
					if($juneT=="") $juneT=0;
					$julyT=$admin->getAmount('July', 'Tech Support');
					if($julyT=="") $julyT=0;
					$augT=$admin->getAmount('August', 'Tech Support');
					if($augT=="") $augT=0;
					$septT=$admin->getAmount('September', 'Tech Support');
					if($septT=="") $septT=0;
					$octT=$admin->getAmount('October', 'Tech Support');
					if($octT=="") $octT=0;
					$novT=$admin->getAmount('November', 'Tech Support');
					if($novT=="") $novT=0;
					$decT=$admin->getAmount('December', 'Tech Support');
					if($decT=="") $decT=0;
					
					//MIS
					$janM=$admin->getAmount('January', 'MIS');
					if($janM=="") $janM=0;
					$febM=$admin->getAmount('February', 'MIS');
					if($febM=="") $febM=0;
					$marchM=$admin->getAmount('March', 'MIS');
					if($marchM=="") $marchM=0;
					$aprilM=$admin->getAmount('April', 'MIS');
					if($aprilM=="") $aprilM=0;
					$mayM=$admin->getAmount('May', 'MIS');
					if($mayM=="") $mayM=0;
					$juneM=$admin->getAmount('June', 'MIS');
					if($juneM=="") $juneM=0;
					$julyM=$admin->getAmount('July', 'MIS');
					if($julyM=="") $julyM=0;
					$augM=$admin->getAmount('August', 'MIS');
					if($augM=="") $augM=0;
					$septM=$admin->getAmount('September', 'MIS');
					if($septM=="") $septM=0;
					$octM=$admin->getAmount('October', 'MIS');
					if($octM=="") $octM=0;
					$novM=$admin->getAmount('November', 'MIS');
					if($novM=="") $novM=0;
					$decM=$admin->getAmount('December', 'MIS');
					if($decM=="") $decM=0;
					
					//SYSAD
					$janS=$admin->getAmount('January', 'System Ad');
					if($janS=="") $janS=0;
					$febS=$admin->getAmount('February', 'System Ad');
					if($febS=="") $febS=0;
					$marchS=$admin->getAmount('March', 'System Ad');
					if($marchS=="") $marchS=0;
					$aprilS=$admin->getAmount('April', 'System Ad');
					if($aprilS=="") $aprilS=0;
					$mayS=$admin->getAmount('May', 'System Ad');
					if($mayS=="") $mayS=0;
					$juneS=$admin->getAmount('June', 'System Ad');
					if($juneS=="") $juneS=0;
					$julyS=$admin->getAmount('July', 'System Ad');
					if($julyS=="") $julyS=0;
					$augS=$admin->getAmount('August', 'System Ad');
					if($augS=="") $augS=0;
					$septS=$admin->getAmount('September', 'System Ad');
					if($septS=="") $septS=0;
					$octS=$admin->getAmount('October', 'System Ad');
					if($octS=="") $octS=0;
					$novS=$admin->getAmount('November', 'System Ad');
					if($novS=="") $novS=0;
					$decS=$admin->getAmount('December', 'System Ad');
					if($decS=="") $decS=0;
					
					//NETAD
					$janN=$admin->getAmount('January', 'Network Ad');
					if($janN=="") $janN=0;
					$febN=$admin->getAmount('February', 'Network Ad');
					if($febN=="") $febN=0;
					$marchN=$admin->getAmount('March', 'Network Ad');
					if($marchN=="") $marchN=0;
					$aprilN=$admin->getAmount('April', 'Network Ad');
					if($aprilN=="") $aprilN=0;
					$mayN=$admin->getAmount('May', 'Network Ad');
					if($mayN=="") $mayN=0;
					$juneN=$admin->getAmount('June', 'Network Ad');
					if($juneN=="") $juneN=0;
					$julyN=$admin->getAmount('July', 'Network Ad');
					if($julyN=="") $julyN=0;
					$augN=$admin->getAmount('August', 'Network Ad');
					if($augN=="") $augN=0;
					$septN=$admin->getAmount('September', 'Network Ad');
					if($septN=="") $septN=0;
					$octN=$admin->getAmount('October', 'Network Ad');
					if($octN=="") $octN=0;
					$novN=$admin->getAmount('November', 'Network Ad');
					if($novN=="") $novN=0;
					$decN=$admin->getAmount('December', 'Network Ad');
					if($decN=="") $decN=0;
					?>
					<script type="text/javascript">
						var janR = <?php echo $janR?>;
						var febR = <?php echo $febR?>;
						var marchR = <?php echo $marchR?>;
						var aprilR = <?php echo $aprilR?>;
						var mayR = <?php echo $mayR?>;
						var juneR = <?php echo $juneR?>;
						var julyR = <?php echo $julyR?>;
						var augR = <?php echo $augR?>;
						var septR = <?php echo $septR?>;
						var octR = <?php echo $octR?>;
						var novR = <?php echo $novR?>;
						var decR = <?php echo $decR?>;
						
						//TECH
						var janT = <?php echo $janT?>;
						var febT = <?php echo $febT?>;
						var marchT = <?php echo $marchT?>;
						var aprilT = <?php echo $aprilT?>;
						var mayT = <?php echo $mayT?>;
						var juneT = <?php echo $juneT?>;
						var julyT = <?php echo $julyT?>;
						var augT = <?php echo $augT?>;
						var septT = <?php echo $septT?>;
						var octT = <?php echo $octT?>;
						var novT = <?php echo $novT?>;
						var decT = <?php echo $decT?>;
						
						//SYSAD
						var janS = <?php echo $janS?>;
						var febS = <?php echo $febS?>;
						var marchS = <?php echo $marchS?>;
						var aprilS = <?php echo $aprilS?>;
						var mayS = <?php echo $mayS?>;
						var juneS = <?php echo $juneS?>;
						var julyS = <?php echo $julyS?>;
						var augS = <?php echo $augS?>;
						var septS = <?php echo $septS?>;
						var octS = <?php echo $octS?>;
						var novS = <?php echo $novS?>;
						var decS = <?php echo $decS?>;
						
						//NETAD
						var janN = <?php echo $janN?>;
						var febN = <?php echo $febN?>;
						var marchN = <?php echo $marchN?>;
						var aprilN = <?php echo $aprilN?>;
						var mayN = <?php echo $mayN?>;
						var juneN = <?php echo $juneN?>;
						var julyN = <?php echo $julyN?>;
						var augN = <?php echo $augN?>;
						var septN = <?php echo $septN?>;
						var octN = <?php echo $octN?>;
						var novN = <?php echo $novN?>;
						var decN = <?php echo $decN?>;
						
						//MIS
						var janM = <?php echo $janM?>;
						var febM = <?php echo $febM?>;
						var marchM = <?php echo $marchM?>;
						var aprilM = <?php echo $aprilM?>;
						var mayM = <?php echo $mayM?>;
						var juneM = <?php echo $juneM?>;
						var julyM = <?php echo $julyM?>;
						var augM = <?php echo $augM?>;
						var septM = <?php echo $septM?>;
						var octM = <?php echo $octM?>;
						var novM = <?php echo $novM?>;
						var decM = <?php echo $decM?>;
						
						var myChart = new JSChart('graph', 'line');
						myChart.setDataArray([["",0],["Jan", janT], ["Feb", febT], ["March", marchT], ["April", aprilT], ["May", mayT], ["June", juneT], ["July", julyT], ["Aug", augT], ["Sept", septT], ["Oct", octT], ["Nov", novT], ["Dec", decT]],"tech");
						myChart.setDataArray([["",0],["Jan", janS], ["Feb", febS], ["March", marchS], ["April", aprilS], ["May", mayS], ["June", juneS], ["July", julyS], ["Aug", augS], ["Sept", septS], ["Oct", octS], ["Nov", novS], ["Dec", decS]],"sysad");
						myChart.setDataArray([["",0],["Jan", janN], ["Feb", febN], ["March", marchN], ["April", aprilN], ["May", mayN], ["June", juneN], ["July", julyN], ["Aug", augN], ["Sept", septN], ["Oct", octN], ["Nov", novN], ["Dec", decN]],"netad");
						myChart.setDataArray([["",0],["Jan", janM], ["Feb", febM], ["March", marchM], ["April", aprilM], ["May", mayM], ["June", juneM], ["July", julyM], ["Aug", augM], ["Sept", septM], ["Oct", octM], ["Nov", novM], ["Dec", decM]],"mis");
						myChart.setDataArray([["",0],["Jan", janR], ["Feb", febR], ["March", marchR], ["April", aprilR], ["May", mayR], ["June", juneR], ["July", julyR], ["Aug", augR], ["Sept", septR], ["Oct", octR], ["Nov", novR], ["Dec", decR]],"rent");
						myChart.setTitle('JR/Section');
						myChart.setTitleColor('#8E8E8E');
						myChart.setTitleFontSize(11);
						myChart.setAxisNameX('');
						myChart.setAxisNameY('');
						myChart.setAxisColor('#8420CA');
						myChart.setAxisValuesColor('#949494');
						//myChart.setAxisPaddingLeft(100);
						//myChart.setAxisPaddingRight(120);
						myChart.setAxisPaddingTop(50);
						myChart.setAxisPaddingBottom(40);
						myChart.setAxisValuesDecimals(2);
						myChart.setAxisValuesNumberX(1);
						myChart.setIntervalStartY(0);
						//myChart.setIntervalEndY(50000);
						//myChart.setAxisValuesNumberY(1);
						myChart.setShowXValues(false);
						myChart.setGridColor('#C5A2DE');
						myChart.setLineColor('#B13B32');
						myChart.setLineWidth(2);
						myChart.setFlagColor('#9D12FD');
						myChart.setFlagRadius(4);
						//myChart.setTooltip(["Jan"]);
						//myChart.setTooltip(["Feb"]);
						//myChart.setTooltip(["March"]);
						//myChart.setTooltip(["April"]);
						//myChart.setTooltip(["May"]);
						//myChart.setTooltip(["June"]);
						//myChart.setTooltip(["July"]);
						//myChart.setTooltip(["Aug"]);
						//myChart.setTooltip(["Sept"]);
						//myChart.setTooltip(["Oct"]);
						//myChart.setTooltip(["Nov"]);
						//myChart.setTooltip(["Dec"]);
						myChart.setLineColor('#A4D314', 'sysad');
						myChart.setLineColor('#BBBBBB', 'netad');
						myChart.setLineColor('#142214', 'rent');
						myChart.setLineColor('#00BB4B', 'mis');
						myChart.setLegendForLine('tech','Tech Support');
						myChart.setLegendForLine('mis','MIS');
						myChart.setLegendForLine('sysad','System Ad');
						myChart.setLegendForLine('netad','Network Ad');
						myChart.setLegendForLine('rent','Rent to Own');
						myChart.setLegendShow(true);
						//myChart.setLegendPosition(430,30);
						myChart.setSize(450,200);
						myChart.setBackgroundImage('chart_bg.jpg');
						myChart.draw();
					</script>
				</fieldset>
			</div>
		</div>
	</div>
</body>
</html>