<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	if($sessionUserType == "Executive"){
		header('Location: job_request_exec.php');
	}
	else if($sessionUserType == "Manager"){
		header('Location: job_request_manager.php');
	}
	$user = new functionalityManager;
	$listOfJR = $user->retrieveListOfJR($sessionUserSection);
	if(isset($_POST["back"])){
		header('Location: #top');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script type="text/javascript">
			function checkIfNullStatus(){
				var a = document.getElementById('status').value;
				if(a=="")
					return false
				else{
					return true;
				}
			}
			function checkIfNullClient(){
				var a = document.getElementById('office').value;
				if(a=="")
					return false
				else{
					return true;
				}
			}
			function hidehide(){
			var a = document.getElementById('office').value;
			if(a=='Others'){
				document.getElementById('others').style.display='block';
				document.getElementById('reqOther').required=true;
				document.getElementById('reqOther').name='office';
			}else{
				document.getElementById('others').style.display='none';
				document.getElementById('reqOther').required=false;
				document.getElementById('reqOther').name='clecle';
			}
		}
		</script>
		<title>.::ITC Billing System::.</title>

	</head>

	<body>
		<div id ="MainContainer">
			<div id="justSpace">
				<br/>
			</div>
			<!---
			<a href="home_exec_manager.php">Home</a>	 |
			<a href="job_request_manager.php">Job Request</a>		 |
			<a href="soa.php">Statement Of Account</a>		 |
			<a href="renttoown.php">Rent To Own</a>		 |
			<a href="income.php">Income</a>
			<br/>--->
			<div id = "functions">
				
				<fieldset>
				<legend>Functions</legend>
				
				<div id="addNew">
					<form method="post" action="addJR.php" id="functions">
						<input type="submit" value="Add Job Request" id="add" name="add" style="width: 131px"/>
					</form>
				</div>
				<div id = "searchNum">
					<form method="post">
						<input type="search" required placeholder="Search JR Number" id="srJrNum" name="srJrNum" />
						<br/><input type="submit" id="goJr" name="goJr" value="Search"/><br/><br/>
					</form>
				</div>
				
				<div id = "groupStat">
					<br/>
					<form method="post" onsubmit="return checkIfNullStatus()" action="">
						<select id="status" name="status">
						<option value="">Choose one..</option>
						<option value="In Process">In Process</option>
						<option value="Pending">Pending</option>
						<option value="Done">Done</option>
						<option value="Cancelled">Cancelled</option>
						</select>
						<br/><input type="submit" id="stat" name="stat" value="View By Status"/>
					</form>
				</div>
				<div id = "clientStat">
					<br/>
					<form method="post" onsubmit="return checkIfNullClient()" action="">
						<select id="office" name="office" style="width:80%;" onchange="hidehide()">
						<?php
						
							echo '<option value="">Choose one..</option>';
							
							$stmt = "SELECT DISTINCT client_office FROM job_request WHERE section='$sessionUserSection';";
							$result = mysqli_query($con,$stmt);
							while($row=mysqli_fetch_assoc($result)){
								echo '<option value="'.$row['client_office'].'">'.$row['client_office'].'</option>';
							}
							echo '<option value="Others">Others, please specify..</option>';
						?>
						</select>
						<div id="others" style="display:none">
							<input type="text" style="width:80%" id="reqOther" name="office" placeholder="Office / Unit"/>
						</div>
						<input type="submit" id="clientOffice" name="clientOffice" value="View By Office"/>
					</form>
				</div>
				
				</fieldset>
				<br/>
				<div id="detalye">
					<?php
						
						echo '<fieldset>';
						echo '<legend>Details</legend>';
							echo '<table>';
							echo '<col width=100>';
								echo '<tr>';
									echo '<td>';
										echo "Quantity: ";
									echo '</td>';
									echo '<td>';
										$stmt = "SELECT count(*) FROM job_request
											where section = '$sessionUserSection'";

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
											where section = '$sessionUserSection' and status='In Process'";

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
											where section = '$sessionUserSection' and status='Pending'";

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
											where section = '$sessionUserSection' and status='Done'";

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
											where section = '$sessionUserSection' and status='Cancelled'";

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
										where status='Done' and section='$sessionUserSection' and payment_status='Unpaid'";
										
										
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
										where status='Done' and section='$sessionUserSection' and payment_status='Paid'";
										
										$result = mysqli_query($con,$stmt);
										$a=mysqli_fetch_array($result);
										echo $a[0];
									echo '</td>';
								echo '</tr>';
							echo '</table>';
						echo '</fieldset>';
					
					?>
					
				</div>
				
			</div>
		
			<div id="table_JR">
				<?php
					include("JR_search.php");
					include("JR_table.php");
				?>
			</div>
			<br/>
		</div>
	</body>
</html>