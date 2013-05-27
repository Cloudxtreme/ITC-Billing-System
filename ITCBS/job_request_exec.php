<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	if($sessionUserType == "Manager"){
		header('Location: job_request_manager.php');
	}
	else if($sessionUserType == "User(Encoder)"){
		header('Location: job_request_user.php');
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
<title>.::ITC Billing System::.</title>
<script type="text/javascript">
	function checkIfNullSearch(){
		var a = document.getElementById('section').value;
		if(a=="")
			return false
		else{
			return true;
		}
	}
</script>
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
				<div id = "searchNum" action="">
					<form method="post" onsubmit="return checkIfNullSearch()">
						Group By:<br/>
						<select id="section" name="section" style="width:130px;">
						<option value="">Choose one..</option>
						<option value="Tech Support">Tech Support</option>
						<option value="System Ad">System Ad</option>
						<option value="Network Ad">Network Ad</option>
						<option value="Rent to Own">Rent to Own</option>
						<option value="MIS">MIS</option>
						</select>
						<br/><input type="submit" id="perSection" name="perSection" value="Filter"/>
					</form>
				</div>
				
				</fieldset>
				<br/>
				<div id="detalye">
					<?php
					if(isset($_POST['perSection'])){
						$section=$_POST['section'];
					}
						
						echo '<fieldset>';
						echo '<legend>Details</legend>';
							echo '<table>';
							echo '<col width=100>';
								echo '<tr>';
									echo '<td>';
										echo "Quantity: ";
									echo '</td>';
									echo '<td>';
										if(isset($_POST['perSection']))
											$stmt = "SELECT count(*) FROM job_request where section='$section'";
										else $stmt = "SELECT count(*) FROM job_request";

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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='In Process' and section='$section'";
										}else{	
											$stmt = "SELECT count(*) FROM job_request
											where status='In Process'";
										}
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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='Pending' and section='$section'";
										}else{
											$stmt = "SELECT count(*) FROM job_request
											where status='Pending'";
										}
										
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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='Done' and section='$section'";

										}else{
											$stmt = "SELECT count(*) FROM job_request
											where status='Done'";
										}
										
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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='Cancelled' and section='$section'";
										}else{
										
											$stmt = "SELECT count(*) FROM job_request
											where status='Cancelled'";
										}
										
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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='Done' and section='$section' and payment_status='Unpaid'";
										}else{
										
											$stmt = "SELECT count(*) FROM job_request
											where status='Done' and payment_status='Unpaid'";
										}
										
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
										if(isset($_POST['perSection'])){
											$stmt = "SELECT count(*) FROM job_request
											where status='Done' and section='$section' and payment_status='Paid'";
										}else{
										
											$stmt = "SELECT count(*) FROM job_request
											where status='Done' and payment_status='Paid'";
										}
										
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