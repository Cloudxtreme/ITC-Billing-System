<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	$user = new functionalityManager;
	
	if(!isset($sessionUsername)){
		header('Location: index.php');
	}
	
	//<iframe src="income.php" border="none" height="100px"></iframe>
	$date_today=date('y-m-d');
	$total_amount = 300000.00;
	
	$stmt = "SELECT sum(amount) FROM job_request WHERE section='Rent to Own' and payment_status = 'Unpaid';";
	$result = mysqli_query($con,$stmt);
	$a=mysqli_fetch_array($result);
	
	$rto_available = $total_amount - (float)$a[0];
	
	
	$paid=0;
	
	$stmt2 = "SELECT distinct jr_parent_number as jr from rent_to_own_monthly
		where payment_status='Paid'";
	$result2 = mysqli_query($con,$stmt2);
	
	while($a=mysqli_fetch_assoc($result2)){
		$jr = $a['jr'];
		
		$stmt3 = "SELECT count(*) from rent_to_own_monthly
			where payment_status='Paid' and jr_parent_number='$jr'";
		$result3 = mysqli_query($con,$stmt3);
		$a=mysqli_fetch_array($result3);
		$count=$a[0];
		
		$stmt3 = "SELECT total_amount,terms from rent_to_own
			where jr_number='$jr'";
		$result3 = mysqli_query($con,$stmt3);
		$row=mysqli_fetch_assoc($result3);
		$totalMain=$row['total_amount'];
		$terms=$row['terms'];
		
		if($terms=="12 months"){
			$paid = $paid + ($totalMain/12 * $count);
		}else{
			$paid = $paid + ($totalMain/6 * $count);
		}
	}
	
	
	
	$rto_available = $rto_available + $paid;
	$rto_unpaid = $total_amount - $rto_available;
	
	$unpaidArray = array();
	$monthArray =array();
	$yearArray = array();
	
	$month=null;
	if(isset($_POST['check'])){
		if($_POST['need']>0){
			if($_POST['need'] <= $rto_available ){
				$month = date('M Y');
			}
			else{
				$needed= $_POST['need'];
				
				$toCompute = $needed - $rto_available;
				
				$stmt = "SELECT sum(monthly_payment/1.12) as sum, monthname(current_date) as month, year(current_date) as year FROM rent_to_own_monthly
					WHERE bill_status='Billed' and payment_status = 'Unpaid';";
				$result = mysqli_query($con,$stmt);
				$row=mysqli_fetch_assoc($result);
				
				$unpaid = $row['sum'];
				$unpaidArray[]=(int)$unpaid;
				$monthArray[]=$row['month'];
				$yearArray[]=$row['year'];
				$i=0;

				if($unpaid>=$toCompute)
					$month = date('M Y');
				else{
					while($toCompute>$unpaid){
						$stmt = "SELECT sum(monthly_payment/1.12) as sum, monthname(DATE_ADD(current_date, INTERVAL $i MONTH)) as month,year(DATE_ADD(current_date, INTERVAL $i MONTH)) as year FROM rent_to_own_monthly
							where payment_status='Unpaid' and
							monthname(date_to_be_paid)=monthname(DATE_ADD(current_date, INTERVAL $i MONTH)) and
							year(date_to_be_paid)=year(DATE_ADD(current_date, INTERVAL $i MONTH));";
						$result = mysqli_query($con,$stmt);
						$row=mysqli_fetch_assoc($result);
						$unpaid = $unpaid + $row['sum'];
						$unpaidArray[]=(int)$unpaid;
						$monthArray[]=$row['month'];
						$yearArray[]=$row['year'];
						$i++;
					}
					$month = $row['month']." , ".$row['year'];
				}
				
	
			}
		}
		else{
			$month = "N/A"; 
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script type="text/javascript" src="js/jscharts.js">
	
	</script>
	<title>.::ITC Billing System::.</title>
	<body>
		<!----
		<a href="home_exec_manager.php">Home</a>	 |
		<a href="job_request_manager.php">Job Request</a>		 |
		<a href="soa.php">Statement Of Account</a>		 |
		<a href="renttoown.php">Rent To Own</a>		 |
		<a href="income.php">Income</a>
		--->
		<br/>
		<?php
			//include("account_info.php");
		?>
		<div id="fundLeft">
			<div id="rto funds">
				<form>
					<fieldset>
					<legend>Funds</legend>
					<table>
					
						<tr>
							<td class="highlight2"><label for="available">Available Amount</label></td>
							<?php echo"<td><input type='text' name='available' readonly='readonly' id='available' value=' $rto_available ' style='text-align:center;'/></td>"; ?>
						</tr>
						<br/>
						<tr>
							<td class="highlight2"><label for="unpaid">Unpaid Amount</label></td>
							<?php echo"<td><input type='text' name='unpaid' readonly='readonly' id='unpaid' value=' $rto_unpaid ' style='text-align:center;'/></td>"; ?>
						</tr>
						<tr>
							
						</tr>
						<tr>
							<td> </td>
							<td> </td>
						</tr>
					</table>
					</fieldset>
				</form>
			</div>
			<br/>
			<div id="rtoProjection">
				<form method="post" action="">
					<fieldset>
					<legend>Availablity</legend>
					<table>
						<tr>
							<td class="highlight2"><label for="need">Amount Needed</label></td>
							<?php 
								if(isset($_POST['need'])) echo "<td><input type='number' min='0' max='300000' value=".$_POST['need']." name='need' id='need'  style='text-align:center; width:154px;'/></td>";
								else echo "<td><input type='number' min='0' max='300000' value='0' name='need' id='need'  style='text-align:center; width:154px;'/></td>";
							?>
							
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="check" value="Check" style="text-align:center;"/></td>
						</tr>
						<br/>
						<tr>
							<td class="highlight2"><label for="available">Month Available </label></td>
							<?php echo"<td><input type='text' name='available' disabled id='available' value='$month' style='text-align:center; color:Blue'/></td>"; ?>
						</tr>
					</table>	
						<?php
						if(count($unpaidArray)>1){
							echo '<br/><fieldset>';
							echo '<legend>Details</legend>';
							echo '<table>';
								echo '<col width="150px">';
								echo '<tr>';
									echo '<td>Month</td>';
									echo '<td>Amount</td>';
								echo '</tr>';
						}
						for($i=0 ;$i<count($unpaidArray) ; $i++){
							echo '<tr>';
								echo '<td>';
									echo $monthArray[$i]." , ".$yearArray[$i];
								echo '</td>';
								echo '<td>';
									echo $unpaidArray[$i]+$rto_available;
								echo '</td>';
							echo '</tr>';
						}
						echo '</table>';
						echo '</fieldset>';
						?>
					
					</fieldset>
				</form>
			</div>
		</div>
		<div id="fundRight">
			<?php
				$stmt = "SELECT DISTINCT client_name from rent_to_own_monthly;";
				$result = mysqli_query($con,$stmt);
			
				echo '<fieldset>';
				echo '<legend>Details</legend>';
					echo '<br/><table class="rto" border=1px cellspacing=0 >';
						echo '<tr>';
							echo '<tr>';
								echo '<th class="titleTable" colspan=5>';
									echo "Rent to Own";
								echo '</th>';
							echo '</tr>';
							echo '<th style="width:40%;">';
								echo "Client";
							echo '</th>';
							echo '<th style="width:20%;">';
								echo "Unpaid";
							echo '</th>';
							echo '<th style="width:20%;">';
								echo "Paid";
							echo '</th>';
							echo '<th style="width:20%;">';
								echo "Total";
							echo '</th>';
						echo '</tr>';
						
						while($row=mysqli_fetch_assoc($result)){
							echo '<tr>';
								echo '<td>';
									echo $row['client_name'];
									$client = $row['client_name'];
									$stmt2 = "SELECT sum(monthly_payment) from rent_to_own_monthly
										where client_name='$client';";
									$result2 = mysqli_query($con,$stmt2);
									$total=mysqli_fetch_array($result2);
			
									$stmt2 = "SELECT sum(monthly_payment) from rent_to_own_monthly
										where client_name='$client' and payment_status='Paid';";
									$result2 = mysqli_query($con,$stmt2);
									$paid=mysqli_fetch_array($result2);
									
									$unpaid = $total[0] - $paid[0];
								echo '</td>';
								echo '<td>';
									echo $unpaid;
								echo '</td>';
								echo '<td>';
									if($paid[0]!=NULL)
										echo $paid[0];
									else echo 0;
								echo '</td>';
								echo '<td>';
									echo $total[0];
								echo '</td>';
							echo '</tr>';
							if($paid[0]==$total[0]){
								$stmt2 = "SELECT distinct jr_parent_number, max(count) as count from rent_to_own_monthly
									where client_name='$client';";
								$result2 = mysqli_query($con,$stmt2);
								$row=mysqli_fetch_assoc($result2);
								$jr=$row['jr_parent_number'];
								$count=$row['count'];
								
								
								$stmt2 = "UPDATE job_request SET bill_status='Billed', payment_status='Paid',
									date_paid=(SELECT date_paid from rent_to_own_monthly where jr_parent_number='$jr' and count=$count),
									date_billed=(SELECT date_billed from rent_to_own_monthly where jr_parent_number='$jr' and count=$count)
									where jr_number = '$jr';";
								$result2 = mysqli_query($con,$stmt2);
							
							}
						}
						echo '<tr>';
							echo '<th style="color:#7b1113;">';
								echo "Total";
								$client = $row['client_name'];
								$stmt2 = "SELECT sum(monthly_payment) from rent_to_own_monthly;";
								$result2 = mysqli_query($con,$stmt2);
								$total=mysqli_fetch_array($result2);
		
								$stmt2 = "SELECT sum(monthly_payment) from rent_to_own_monthly
									where payment_status='Paid';";
								$result2 = mysqli_query($con,$stmt2);
								$paid=mysqli_fetch_array($result2);
								
								$unpaid = $total[0] - $paid[0];
							echo '</th>';
							echo '<td>';
								echo $unpaid;
							echo '</td>';
							echo '<td>';
								if($paid[0]!=NULL)
									echo $paid[0];
								else echo 0;
							echo '</td>';
							echo '<td>';
								echo $total[0];
							echo '</td>';
						echo '</tr>';
					echo '</table><br/>';
				echo '</fieldset>';
			?>
		</div>
	</body>
</html>




