<?php
	//include("functions.php");
	include("sessionGet.php");
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	if(isset($_POST["back"])){
		header('Location: #top');
	}
	if(isset($_POST["revert"])){
		$search=0;
		header('Location: #top');
	}
	
	$con = mysqli_connect("localhost","root","","itcbs_db");
	$stmt = "SELECT soa_number FROM soa;";
	$result = mysqli_query($con,$stmt);
	$editting=0;
	$saving=0;
	$search=0;
	
	//checking for the submit buttons are triggered
	while($row=mysqli_fetch_assoc($result)){
		$soaToBeEdited = $row['soa_number'];
		if(isset($_POST["$soaToBeEdited"])){
			$editting=1;
			break;
		}
		$Save = "Save";
		if(isset($_POST["$soaToBeEdited$Save"])){
			$stmt = "SELECT sum(amount) FROM job_request
			WHERE soa_number = $soaToBeEdited;";
			if($sessionUserSection=="Rent to Own"){
				$stmt = "SELECT sum(monthly_payment) FROM rent_to_own_monthly 
				WHERE soa_number = $soaToBeEdited;";
			}
			
			$result = mysqli_query($con,$stmt);
			$a = mysqli_fetch_array($result);
			$total = $a[0];
			
			$saving=1;
			break;
		}
	}
	
	if(isset($_POST["search"])){
		$search = 1;
		$soaToView = $_POST["soaToView"];
		
	}
	
	//saving the updates in a SOA
	if($saving==1){
		
		$PaymentStatus = $_POST["paymentStatus"];
		
		if(isset($_POST["datePaid"]))
			$DatePaid = $_POST["datePaid"];
		else $DatePaid = NULL;
		
		if(isset($_POST["orNum"]))
			$ORNum = $_POST["orNum"];
		else $ORNum = NULL;
	
		if(isset($_POST["checkNum"]))
			$CheckNum = $_POST["checkNum"];
		else $CheckNum = NULL;
		
		$stmt = "UPDATE soa SET total_amount=$total, payment_status='$PaymentStatus',date_paid='$DatePaid', or_number='$ORNum', check_number='$CheckNum' where soa_number=$soaToBeEdited;";
		$result = mysqli_query($con,$stmt);
		echo mysqli_error($con);
		if($sessionUserSection!="Rent to Own")
			$stmt = "UPDATE job_request SET payment_status='$PaymentStatus', date_paid='$DatePaid' where soa_number=$soaToBeEdited;";
		else $stmt = "UPDATE rent_to_own_monthly SET payment_status='$PaymentStatus', date_paid='$DatePaid' where soa_number=$soaToBeEdited;";
		$result = mysqli_query($con,$stmt);
		echo mysqli_error($con);
	}
	
	//initialization of soa number
	$stmt = "SELECT max(soa_number) FROM soa;";
	$result = mysqli_query($con,$stmt);
	$a = mysqli_fetch_array($result);
	
	$yearStmt = "SELECT year(current_date);";
	$year = mysqli_query($con,$yearStmt);
	$b= mysqli_fetch_array($year);
	
	if($a[0]==NULL)
		$soaNum = 100;
	else
		$soaNum = $a[0]+1;
	$soaMain = "BN-".$b[0]."-".$soaNum;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Statement of Account</title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<script type="text/javascript">
		
		function hidePrint(){
			document.getElementById('back').style.display='none';
			document.getElementById('print').style.display='none';
			window.print();
			document.getElementById('back').style.display='block';
			document.getElementById('print').style.display='block';
		}
		
		function hidePrintEdit(){
			document.getElementById('paymentOptions').style.display='none';
			window.print();
			document.getElementById('paymentOptions').style.display='block';
		}
		function hidePrintView(){
			document.getElementById('updateSummary').style.display='none';
			window.print();
			document.getElementById('updateSummary').style.display='block';
		}
		
		function hidehide(){
			var a = document.getElementById('cliente').value;
			if(a=='Others'){
				document.getElementById('others').style.display='block';
				document.getElementById('reqOther').required=true;
				document.getElementById('reqOther').name='cliente';
			}else{
				document.getElementById('others').style.display='none';
				document.getElementById('reqOther').required=false;
				document.getElementById('reqOther').name='clecle';
			}
		}
		function showHideDetails(value){
			
			if(value=="Paid"){
				document.getElementById('paidDetails').style.display='block';
				document.getElementById('orNum').required=true;
			}
			if(value=="Unpaid"){
				document.getElementById('paidDetails').style.display='none';
				document.getElementById('orNum').required=false;
			}
		}
		
		function checkIfNull(){
			var a = document.getElementById('cliente').value;
			if(a=="")
				return false
			else{
				var prompt = confirm("Are you sure with your inputs? You won't be able to edit them?");
				return prompt;
			}
		}
		
		function checkIfNullSearch(){
			var a = document.getElementById('soaToView').value;
			if(a=="")
				return false
			else{
				return true;
			}
		}
		</script>
	</head>
	<body>
	<!--
	<div id="menuTab">
	<a href="home_exec_manager.php">Home</a>	 |
	<a href="job_request_manager.php">Job Request</a>		 |
	<a href="soa.php">Statement Of Account</a>		 |
	<a href="renttoown.php">Rent To Own</a>		 |
	<a href="income.php">Income</a>
	</div>
	---->
	
	<?php
		/*echo '<div id="acctinfo">';
			include("account_info.php");
		echo '</div>';
		*/
		
		
		//displays the created SOA's
		//chosing the client offfice to be issued upon
		 if(!isset($_POST['generate']) && $editting!=1){
			
			//looks for job requests which are not yet billed but already done
			//and get its client office
			if($sessionUserType!="Executive"){
				//-------------Not for Executives-------------//
				echo '<div id="soaLeft">';
				
				echo '<fieldset>';
				echo '<legend>Generate SOA</legend>';
				
				if($sessionUserSection!="Rent to Own"){
					//--------------Not for Rent to Own-----------------//
					$stmt = "SELECT DISTINCT client_office FROM job_request WHERE section='$sessionUserSection' and bill_status LIKE 'Unbilled' and status like 'Done' and amount!=0;";
					$result = mysqli_query($con,$stmt);
					
					echo '<form method="post" onsubmit="return checkIfNull()" action="">';
						echo '<table style="width:100%">';
						echo '<col width="150">';
						
						
						echo '<tr>';
							echo '<td>';
								echo 'Name:';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" name="clientName" required/>';
							echo '</td>';
						echo '</tr>';
						
						echo '<tr>';
							echo '<td>';
								echo 'Designation:';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" name="clientDesig" required/>';
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>';
								echo 'Office/Unit:	';
							echo '</td>';
							echo '<td>';
								echo '<select name="cliente" id="cliente" onchange="hidehide()">';
								echo '<option value="">Choose one..</option>';
								
								while($row=mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['client_office'].'">'.$row['client_office'].'</option>';
								}
								echo '<option value="Others">Others, please specify..</option>';
								echo '</select>';
							echo '</td>';
								
							echo '<td><div id="others" style="display:none">';
							echo '<input type="text" style="width:200px" id="reqOther" name="cliente" placeholder="Office / Unit"/>';
							echo '</div></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>';
								echo 'Amount (in words):';
							echo '</td>';
							echo '<td>';
								echo '<input type="text" style="width:300px" name="amountWords" required/>';
							echo '</td>';
						echo '</tr>';
						echo '</table>';
						
						echo '<br/>';
						echo '<input type="submit" name="generate" value="Generate" style="width:200px">';
					echo '</form>';
				}else{
					//----------------Only for Rent to Own--------------//
					$stmt = "SELECT DISTINCT client_name FROM rent_to_own_monthly
						WHERE bill_status='Unbilled' and payment_status='Unpaid';";
					$result = mysqli_query($con,$stmt);
					
					echo '<form method="post" onsubmit="return checkIfNull()" action="">';
						echo '<table>';
						echo '<col width="150"><col width="200">';
						
						echo '<tr>';
							echo '<td>';
								echo 'Client Name:';
							echo '</td>';
							echo '<td>';
								echo '<select name="cliente" id="cliente" onchange="hidehide()" style="width:100%">';
								echo '<option value="">Choose one..</option>';
								
								while($row=mysqli_fetch_assoc($result)){
									echo '<option value="'.$row['client_name'].'">'.$row['client_name'].'</option>';
								}
								echo '</select>';
							echo '</td>';
							echo '<tr>';
								echo '<td>';
									echo 'Month Date:';
								echo '</td>';
								echo '<td>';
									$stmt = "SELECT current_date;";
									$result = mysqli_query($con,$stmt);
									$a=mysqli_fetch_array($result);
									echo '<input type="date" name="monthToPay" value="'.$a[0].'"required/>';
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo 'Amount (in words):';
								echo '</td>';
								echo '<td>';
									echo '<input type="text" name="amountWords" required/>';
								echo '</td>';
							echo '</tr>';
						echo '</table>';
						
						echo '<br/>';
						echo '<input type="submit" name="generate" value="Generate" style="width:200px">';
					echo '</form>';
					//--------------------End for Rent to Own------------------//
				}
				echo '</fieldset>';
				echo '<div id="searchBar" name="searchBar">';
					echo '<form method="post" onsubmit="return checkIfNullSearch()" action="">';
						echo '<input type="search" name="soaToView" id="soaToView" placeholder="Enter SOA number here.."/>';
						echo '<input type="submit" value="Search" name="search" id="search"/><br/><br/>';
					echo '</form>';
				echo '</div>';
				echo '</div>';
			}
			
			if($sessionUserType!="Executive")
				echo '<div id="soaRight">';
			else echo '<div style="width:100%;">';
			echo '<fieldset>';
			echo '<legend>Details</legend>';
				echo '<table class="jrDetalye">';
				echo '<col width=100>';
					echo '<tr>';
						echo '<td>';
							echo "Quantity: ";
						echo '</td>';
						echo '<td>';
							if($sessionUserType!="Executive")
								$stmt = "SELECT count(*) FROM soa where section='$sessionUserSection'";
							else $stmt = "SELECT count(*) FROM soa";

							$result = mysqli_query($con,$stmt);
							$a=mysqli_fetch_array($result);
							echo $a[0];
						echo '</td>';
					echo '</tr>';
					echo '<tr>';
						echo '<td>';
							echo "Unpaid: ";
						echo '</td>';
						echo '<td>';
							if($sessionUserType!="Executive"){
								$stmt = "SELECT count(*) FROM soa
									where section='$sessionUserSection' and payment_status = 'Unpaid'";
							}else{
								$stmt = "SELECT count(*) FROM soa
									where payment_status = 'Unpaid'";
							
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
							if($sessionUserType!="Executive"){
								$stmt = "SELECT count(*) FROM soa
									where section='$sessionUserSection' and payment_status = 'Paid'";
							}else{
								$stmt = "SELECT count(*) FROM soa
									where payment_status = 'Paid'";
							
							}
							
							$result = mysqli_query($con,$stmt);
							$a=mysqli_fetch_array($result);
							echo $a[0];
						echo '</td>';
					echo '</tr>';
				echo '</table>';
			echo '</fieldset>';
			echo '<br/>';
			if($sessionUserSection!="Rent to Own"){
				echo '<fieldset>';
				echo '<legend>Job Requests</legend>';
					echo '<table class="jrDetalye">';
					echo '<col width=100>';
						echo '<tr>';
							echo '<td>';
								echo "Quantity: ";
							echo '</td>';
							echo '<td>';
								if($sessionUserType!="Executive")
									$stmt = "SELECT count(*) FROM job_request where section='$sessionUserSection'";
								else $stmt = "SELECT count(*) FROM job_request";
								
								$result = mysqli_query($con,$stmt);
								$a=mysqli_fetch_array($result);
								echo $a[0];
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>';
								echo "Unbilled: ";
							echo '</td>';
							echo '<td>';
								if($sessionUserType!="Executive"){
									$stmt = "SELECT count(*) FROM job_request
										where section='$sessionUserSection' and bill_status='Unbilled'";
								}else{
									$stmt = "SELECT count(*) FROM job_request
										where bill_status='Unbilled'";
								}
								$result = mysqli_query($con,$stmt);
								$a=mysqli_fetch_array($result);
								echo $a[0];
							echo '</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td>';
								echo "Billed: ";
							echo '</td>';
							echo '<td>';
								if($sessionUserType!="Executive"){
									$stmt = "SELECT count(*) FROM job_request
										where section='$sessionUserSection' and bill_status='Billed'";
								}else{
									$stmt = "SELECT count(*) FROM job_request
										where bill_status='Billed'";
								}
								$result = mysqli_query($con,$stmt);
								$a=mysqli_fetch_array($result);
								echo $a[0];
							echo '</td>';
						echo '</tr>';
					echo '</table>';
				echo '</fieldset>';
			}
			echo '</div>';
			echo '<br/>';
			
			if($search!=1){
				//---------------If not searching------------//
				//displays the Unpaid SOAs 
				if($sessionUserType!="Executive"){
					$stmt = "SELECT soa_number, soa_main_number, client_office, client_name,total_amount, payment_status,section FROM soa
						where section='$sessionUserSection' and payment_status like 'Unpaid';";
				}else{
					$stmt = "SELECT soa_number, soa_main_number, client_office, total_amount, payment_status, client_name,section FROM soa
						where payment_status like 'Unpaid';";
				}
				$result = mysqli_query($con,$stmt);
				echo '<div id="soaScrollable">';
				echo '<table class="soaTable" cellspacing="0" border=1px;>';
				echo '<tr>';
					echo '<th class="titleTable" colspan=5>';
						echo "Unpaid Statements Of Account";
					echo '</th>';
				echo '</tr>';
				echo '<tr>';
					echo '<th style="width:25%;">';
						echo "SOA Number";
					echo '</th>';
					echo '<th  style="width:35%;">';
						echo "Client";
					echo '</th>';
					echo '<th  style="width:20%;">';
						echo "Amount";
					echo '</th>';
					if($sessionUserType!="Executive"){
						echo '<th style="width:20%;">';
							echo "Update";
						echo '</th>';
					}else{
						echo '<th style="width:20%;">';
							echo "View";
						echo '</th>';
					}
				echo '</tr>';
				while($row=mysqli_fetch_assoc($result)){
					echo '<tr>';
						echo '<td  style="width:25%;">';
							echo $row['soa_main_number'];
							$soa = $row['soa_number'];
						echo '</td>';
						echo '<td  style="width:35%;">';
							if($row['section']!="Rent to Own")
								echo $row['client_office'];
							else echo $row['client_name'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['total_amount'];
						echo '</td>';
						if($sessionUserType!="Executive"){
							echo '<td style="width:20%;">';
								echo '<form method="post" action="">';
									echo '<input type="submit" style="width:100px;" name="'.$soa.'" value="Update"/>';
								echo '</form>';
							echo '</td>';
						}else{
							echo '<td style="width:20%;">';
								echo '<form method="post" action="">';
									echo '<input type="submit" style="width:100px;" name="'.$soa.'" value="View"/>';
								echo '</form>';
							echo '</td>';
						}
					echo '</tr>';
				}
				echo '</table>';
				echo '</div>';
				echo '<br/>';
				
				//displays the Paid SOAs 
				if($sessionUserType!="Executive"){
					$stmt = "SELECT soa_number, soa_main_number, or_number, client_office, client_name, total_amount, payment_status, section FROM soa 
						where section='$sessionUserSection' and payment_status like 'Paid';";
				}else{
					$stmt = "SELECT soa_number, soa_main_number, or_number, client_office, total_amount, client_name,section,payment_status FROM soa 
						where payment_status like 'Paid';";
				}
				$result = mysqli_query($con,$stmt);
				
				echo '<div id="soaScrollable" >';
				echo '<table class="soaTable" cellspacing="0" border=1px;>';
				echo '<tr>';
					echo '<th class="titleTable" colspan=5>';
						echo "Paid Statements Of Account";
					echo '</th>';
				echo '</tr>';
				echo '<tr>';
					echo '<th style="width:20%;">';
						echo "SOA Number";
					echo '</th>';
					echo '<th style="width:25%;">';
						echo "Client";
					echo '</th>';
					echo '<th style="width:20%;">';
						echo "Amount";
					echo '</th>';
					echo '<th style="width:20%;">';
						echo "OR Number";
					echo '</th>';
					if($sessionUserType!="Executive"){
						echo '<th style="width:15%;">';
							echo "Update";
						echo '</th>';
					}else{
						echo '<th style="width:15%;">';
							echo "View";
						echo '</th>';
					}
				echo '</tr>';
				while($row=mysqli_fetch_assoc($result)){
					echo '<tr>';
						echo '<td  style="width:20%;">';
							echo $row['soa_main_number'];
							$soa = $row['soa_number'];
						echo '</td>';
						echo '<td  style="width:25%;">';
							if($row['section']!="Rent to Own")
								echo $row['client_office'];
							else echo $row['client_name'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['total_amount'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['or_number'];
						echo '</td>';
						if($sessionUserType!="Executive"){
							echo '<td style="width:15%;">';
								echo '<form method="post" action="">';
									echo '<input type="submit" style="width:80px;" name="'.$soa.'" value="Update"/>';
								echo '</form>';
							echo '</td>';
						}else{
							echo '<td style="width:15%;">';
								echo '<form method="post" action="">';
									echo '<input type="submit" style="width:80px;" name="'.$soa.'" value="View"/>';
								echo '</form>';
							echo '</td>';
						}
					echo '</tr>';
				}
				echo '</table>';
				echo '</div>';
				//-----------------End of tables---------------//
			}
			echo '<br/>';
		}
		
		
		//generation of SOA
		if(isset($_POST['generate'])){
			if($_POST['cliente']!=""){
				
				//total amount
				$total = 0;
				//count for number of job requests for inserting
				$count = 0;
				$cliente = $_POST['cliente'];
				$amountWords = $_POST['amountWords'];
				if($sessionUserSection!="Rent to Own"){				
					$name = $_POST['clientName'];
					$designation = $_POST['clientDesig'];
				}else{
					$month = $_POST['monthToPay'];
					$stmt = "SELECT min(count) from rent_to_own_monthly
						where client_name='$cliente' and
						payment_status!='Paid' and
						bill_status!='Billed';";
					$result = mysqli_query($con,$stmt);
					$a = mysqli_fetch_array($result);
					$count=$a[0];
					
					$stmt = "UPDATE rent_to_own_monthly
						set month='$month' where client_name='$cliente'
						and count=$count;";
					$result = mysqli_query($con,$stmt);
				}
				
	
				?>
				<!------------Print Version--------------------------->
				<div id="printVersion">
				<table>
					<col width="125">
					<col width="1700">
					<col width="300">
					<tr>
						<td><img src="UPLBlogo.jpg"  style="width:125px; height:115px;"/></td>
						<td align="center">
							University of the Philippines Los Banos
							<br/>
							<br/>
							Information Technology Center
							<br/>
							Office of the Vice Chancellor for Planning & Development
							<br/>
							Rm 206 Abelardo Samonte Hall, UPLB, College, Laguna
						</td>
						<td>
						<img src="ITClogo.jpg"  style="width:100px; height:100px;"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td><br/><b>Bill No.: </b>
						<?php
						$stmt = "SELECT concat(monthname(current_date), ' ', day(current_date), ', ',year(current_date)) as date;";

						$result = mysqli_query($con,$stmt);
						$a = mysqli_fetch_array($result);
						echo '<input type="text" name="invoiceNo" size="20" value="'.$soaMain.'" readonly="readonly" /><br/>';
						echo '<b>Date: </b>'.$a[0];
						?>
						</td>
					</tr>
				</table>
					<br/>
					<br/>
				<table>
					<col width="100">
					<tr>
					<td style="float:left;">
					TO: 
					</td>
					
					<?php
					if($sessionUserSection!="Rent to Own"){
						echo '<td></td><td>'.$name.'</td></tr>';
						echo '<tr><td></td><td></td><td>'.$designation.'</td></tr>';
						echo '<tr><td></td><td></td><td>'.$cliente.'</td></tr>';
					}else{
						echo '<td></td><td>'.$cliente.'</td></tr>';
						echo '<tr><td></td><td></td><td>Owner</td></tr>';
						echo '<tr><td></td><td></td><td>Personal</td></tr>';
					}
					?>
					
				</table>
					<br/>
					&nbsp; This is to inform you that for the services listed below, your office has incurred a total amount of <b> <?php echo " ".$amountWords.", ";?>Php <?php
					$stmt = "SELECT sum(amount) FROM job_request 
					WHERE section='$sessionUserSection' and client_office like '$cliente' and bill_status like 'Unbilled'
					and status like 'Done' and amount!=0;";

					if($sessionUserSection=="Rent to Own"){
						$stmt = "SELECT monthly_payment FROM rent_to_own_monthly 
						WHERE client_name='$cliente' and bill_status='Unbilled'
						and payment_status='Unpaid' ORDER BY count ASC;";
					}
					$account="Information Technology Center (Acct. #8217200)";
					if($cliente=="Personal" && $sessionUserSection=="Tech Support"){
						$account="UPLB-FI (ACCT. #2009-01ITC)";
					}
					//--------------Pause HEre-----------//
					$result = mysqli_query($con,$stmt);
					$a = mysqli_fetch_array($result);
					$total = $a[0];
					echo $a[0]; ?></b>
					<br/>
					Please prepare the payment to the account of <b><?php echo $account?>.</b>
					<br/>
				<?php
				//looks for job requests of the chosen client office
				//that are already done, but not yet billed, and if it is not free,
				//meaning, there is an amount indicated
				$stmt = "SELECT jr_number, concat(monthname(date_created), ' ', day(date_created), ', ',year(date_created)) as date, service_type, amount FROM job_request 
					WHERE section='$sessionUserSection' and client_office like '$cliente' and bill_status like 'Unbilled'
					and status like 'Done' and amount!=0;";
	
				$result = mysqli_query($con,$stmt);
				
				if($sessionUserSection=="Rent to Own"){
					$stmt = "SELECT jr_number,concat(monthname(month), ', ',year(month)) as date, monthly_payment FROM rent_to_own_monthly 
						WHERE client_name='$cliente' and bill_status='Unbilled'
						and payment_status='Unpaid' ORDER BY count ASC;";
					$result = mysqli_query($con,$stmt);
				}
				
				$count=0;
				
				//table of job requests of the client
				echo '<table class="soaTable" cellspacing="0" border=1px >';
				echo '<tr>';
					echo '<th  style="width:20%;">';
						echo "JR Number";
					echo '</th>';
					echo '<th  style="width:20%px;">';
						echo "Date";
					echo '</th>';
					echo '<th  style="width:40%;">';
						echo "Description";
					echo '</th>';
					echo '<th style="width:20%">';
						echo "Amount";
					echo '</th>';
				echo '</tr>';
				if($sessionUserSection!="Rent to Own"){
					while($row=mysqli_fetch_assoc($result)){
						echo '<tr>';
							echo '<td style="width:20%;">';
								echo $row['jr_number'];
								$id = $row['jr_number'];
							echo '</td>';
							echo '<td style="width:20%;">';
								echo $row['date'];
							echo '</td>';
							echo '<td style="width:40%;text-align:left;">';
								echo $row['service_type'];
							echo '</td>';
							echo '<td style="width:20%;">';
								echo $row['amount'].".00";
							echo '</td>';
						echo '</tr>';
						
						$updatestmt = "UPDATE job_request SET bill_status='Billed', date_billed = current_date, soa_number = $soaNum where jr_number like '$id';";
						$updateresult = mysqli_query($con,$updatestmt);
						$count ++;
					}
				}else{
					//------------------only for Rent to Own-------------//
					$row=mysqli_fetch_assoc($result);
					echo '<tr>';
						echo '<td style="width:20%;">';
							echo $row['jr_number'];
							$id = $row['jr_number'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['date'];
						echo '</td>';
						echo '<td style="width:40%;text-align:left;">';
							echo "Monthly Payment";
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['monthly_payment'];
						echo '</td>';
					echo '</tr>';
					if($id!=NULL){
						$count=1;
						$updatestmt = "UPDATE rent_to_own_monthly SET bill_status='Billed', date_billed = current_date, soa_number = $soaNum where jr_number ='$id';";
						$updateresult = mysqli_query($con,$updatestmt);
					}
					//-----------------End of rent to own---------------//
				}
				echo '</table>';
				$please=0;
				if($sessionUserSection=="Tech Support"){
					if($cliente!="Personal")
						$please=1;
				}
				?>
				<p>If you have any question concerning this statement, please call us at 501-4591, 536-2886 opt. 2, VOIP #100</p>
				<?php
				if($please==1){
					echo "<p>";
					echo "Please inform us the docu track number of the disbursement voucher and service request from number. Kindly contact us at the above tel. nos. or email us at itctechsupport@uplb.edu.ph. Please prepare check payable to UPLB Information Technology Center or pay at the Cash Division credited to account number 8217200.";
					echo "</p>";
				}
				?>
				<p>Thank you.</p>
				<br/>
				<br/>
				<table align="left">
				<!----sag--------->
				<col width="440">
				<col width="300">
				<col width="350">
				<tr>
				<td></td>
				<td></td>
				<td>
				Sincerely,
				<br/>
				<br/>
				<br/>
				<br/>
				<?php
				$head="DANTE GIDEON K. VERGARA";
				$headPosition="Director";
				if($sessionUserSection=="Tech Support"){
					$headPosition="Technical Support Team";
					if($cliente=="Personal"){
						$head="VERNON I. VELASCO";
					}else{
						$head="ALBERTO A. DAO";
					}
				}
				echo $head;
				echo '<br/>'.$headPosition;
				?>
				</td>
				</tr>
				
				<?php
				if($count!=0){
					if($sessionUserSection!="Rent to Own"){
						$addstmt = "INSERT INTO soa(soa_number, soa_main_number,client_name, client_designation, date_issued,section,client_office,total_amount,payment_status,amount_words,account_num,head,head_position,please)
						VALUES($soaNum,'$soaMain','$name','$designation',current_date,'$sessionUserSection','$cliente',$total,'Unpaid','$amountWords','$account','$head','$headPosition',$please);";
					}else{
						$addstmt = "INSERT INTO soa(soa_number, soa_main_number,client_name, client_designation, date_issued,section,client_office,total_amount,payment_status)
						VALUES($soaNum,'$soaMain','$cliente','Owner',current_date,'$sessionUserSection','Personal',$total,'Unpaid');";
					}
					$result = mysqli_query($con,$addstmt);
					echo '<br/>';
					echo mysqli_error($con);
				}else{
					echo "<h2>No Job Requests to be Paid.</h2>";
				}
				echo '</div>';
				echo '<tr>';
				//back button
					echo '<td>';
						echo '<form method="post" action="#top">';
							echo '<input type="submit" id="back" name = "back" value="Back" style="width:100px">';
						echo '</form>';
					echo '</td>';
				echo '</tr>';
				echo '<tr>';
					echo '<td>';
					//print button
						if($count!=0)
							echo '<input type="button" id="print" name = "print" value="Print" style="width:100px" onclick="hidePrint()">';
					echo '</td>';
				echo '</tr>';
				
				echo '</table>';
				
				
			}
		}
		
		
		//updating of SOA
		if($editting==1){
			
			/**************************************Print Version*******************************************************************/
			//total amount
			$total = 0;
			//count for number of job requests for inserting
			$count = 0;
			
			$stmt2 = "SELECT soa_main_number, client_name, please, client_designation, section, client_office, date_issued, or_number, total_amount, amount_words, account_num,head,head_position FROM soa 
					WHERE soa_number=$soaToBeEdited;";

			$result2 = mysqli_query($con,$stmt2);
			$row=mysqli_fetch_assoc($result2);
			
			$soaMain = $row['soa_main_number'];
			$cliente = $row['client_office'];
			$name = $row['client_name'];
			$section = $row['section'];
			$designation = $row['client_designation'];
			$dateIssued = $row['date_issued'];
			$OrNum = $row['or_number'];
			$total = $row['total_amount'];
			$amountWords = $row['amount_words'];
			$account=$row['account_num'];
			$head=$row['head'];
			$headPosition=$row['head_position'];
			$please=$row['please'];
			
			?>
			<div id="printVersion">
			<table>
				<col width="125">
				<col width="1700">
				<col width="300">
				<tr>
					<td><img src="UPLBlogo.jpg"  style="width:125px; height:115px;"/></td>
					<td align="center">
						University of the Philippines Los Banos
						<br/>
						<br/>
						Information Technology Center
						<br/>
						Office of the Vice Chancellor for Planning & Development
						<br/>
						Rm 206 Abelardo Samonte Hall, UPLB, College, Laguna
					</td>
					<td>
					<img src="ITClogo.jpg"  style="width:100px; height:100px;"/>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><br/><b>Bill No.: </b>
					<?php
					
					echo '<input type="text" name="invoiceNo" size="20" value="'.$soaMain.'" readonly="readonly" /><br/>';
					echo '<b>Date: </b>'.$dateIssued;
					?>
					</td>
				</tr>
			</table>
				<br/>
				<br/>
			<table>
				<col width="100">
				<tr>
				<td style="float:left;">
				TO: 
				</td>
				
				<?php
				echo '<td></td><td>'.$name.'</td></tr>';
				echo '<tr><td></td><td></td><td>'.$designation.'</td></tr>';
				echo '<tr><td></td><td></td><td>'.$cliente.'</td></tr>';
				?>
				
			</table>
				<br/>
				&nbsp; This is to inform you that for the services listed below, your office has incurred a total amount of <b> <?php echo " ".$amountWords.", ";?>Php <?php
				echo $total; ?></b>
				<br/>
				Please prepare the payment to the account of <b><?php echo $account;?>.</b>
				<br/>
			<?php
			//looks for job requests of the chosen client office
			//that are already done, but not yet billed, and if it is not free,
			//meaning, there is an amount indicated
			$stmt = "SELECT jr_number, concat(monthname(date_created), ' ', day(date_created), ', ',year(date_created)) as date, service_type, amount FROM job_request 
				WHERE soa_number=$soaToBeEdited;";
				
			if($section=="Rent to Own"){
				$stmt = "SELECT jr_number, concat(monthname(month),', ',year(month)) as date, monthly_payment FROM rent_to_own_monthly
				WHERE soa_number=$soaToBeEdited;";
			}

			$result = mysqli_query($con,$stmt);
			
			$count=0;
			
			//table of job requests of the client
			echo '<table class="soaTable" cellspacing="0" border=1px>';
			echo '<tr>';
				echo '<th  style="width:20%;">';
					echo "JR Number";
				echo '</th>';
				echo '<th  style="width:20%px;">';
					echo "Date";
				echo '</th>';
				echo '<th  style="width:40%;">';
					echo "Description";
				echo '</th>';
				echo '<th style="width:20%">';
					echo "Amount";
				echo '</th>';
			echo '</tr>';
			if($section=="Rent to Own"){
				//------------------only for Rent to Own-------------//
				$row=mysqli_fetch_assoc($result);
				echo '<tr>';
					echo '<td style="width:20%;">';
						echo $row['jr_number'];
						$id = $row['jr_number'];
					echo '</td>';
					echo '<td style="width:20%;">';
						echo $row['date'];
					echo '</td>';
					echo '<td style="width:40%;text-align:left;">';
						echo "Monthly Payment";
					echo '</td>';
					echo '<td style="width:20%;">';
						echo $row['monthly_payment'];
					echo '</td>';
				echo '</tr>';
				//-------------------end of rent to own----------------//
				
			}else{
				while($row=mysqli_fetch_assoc($result)){
					echo '<tr>';
						echo '<td style="width:20%;">';
							echo $row['jr_number'];
							$id = $row['jr_number'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo $row['date'];
						echo '</td>';
						echo '<td style="width:40%;text-align:left;">';
							echo $row['service_type'];
						echo '</td>';
						echo '<td style="width:20%;">';
							echo "PHP ".$row['amount'].".00";
						echo '</td>';
					echo '</tr>';
				}
			}
			echo '</table>';
			
			?>
			<p>If you have any question concerning this statement, please call us at 501-4591, 536-2886 opt. 2, VOIP #100</p>
			<?php
				if($please==1){
					echo "<p>";
					echo "Please inform us the docu track number of the disbursement voucher and service request from number. Kindly contact us at the above tel. nos. or email us at itctechsupport@uplb.edu.ph. Please prepare check payable to UPLB Information Technology Center or pay at the Cash Division credited to account number 8217200.";
					echo "</p>";
				}
			?>
			<p>Thank you.</p>
			<br/>
			<br/>
			<table align="left">
			<!----sag--------->
			<col width="440">
			<col width="300">
			<col width="350">
			<tr>
			<td></td>
			<td></td>
			<td>
			Sincerely,
			<br/>
			<br/>
			<br/>
			<br/>
			<?php
			echo $head;
			echo '<br/>'.$headPosition;
			?>
			</td>
			</tr>
			
			<?php
			
			//echo '<tr>';
			/*echo '<tr>';
				echo '<td>';
					//print button
					echo '<input type="button" id="print" name = "print" value="Print" style="width:100px" onclick="hidePrintView()">';
				echo '</td>';
			echo '</tr>';
			*/
			echo '</table>';
			echo '</div>';
			/***********************************************************************************************************************************/
			
			if($sessionUserType!="Executive"){
				echo '<div id="updateSummary">';
					
					echo '<fieldset>';
					echo '<legend><b>Summary</b></legend>';
					$stmt2 = "SELECT client_name, client_designation, client_office FROM soa 
							WHERE soa_number=$soaToBeEdited;";
					
					$result2 = mysqli_query($con,$stmt2);
					$row=mysqli_fetch_assoc($result2);
					
					$total =0 ;
					$count =0 ;
					echo '<fieldset>';
						echo '<legend>Client Information</legend>';
						echo '<table>';
							echo '<col width="150">';
							echo '<tr><td>Name:</td><td>'.$row['client_name'].'</td></tr>';
							echo '<tr><td>Designation:</td><td>'.$row['client_designation'].'</td></tr>';
							echo '<tr><td>Office/Unit:</td><td>'.$row['client_office'].'</td></tr>';
						echo '</table>';
					echo '</fieldset><br/>';
					
					$stmt = "SELECT jr_number, concat(monthname(date_created), ' ', day(date_created), ', ',year(date_created)) as date, service_type, amount FROM job_request 
							WHERE soa_number=$soaToBeEdited;";
					
					if($sessionUserSection=="Rent to Own"){
						$stmt = "SELECT jr_number, concat(monthname(month),', ',year(month)) as date, monthly_payment FROM rent_to_own_monthly
						WHERE soa_number=$soaToBeEdited;";
					}
					$result = mysqli_query($con,$stmt);
					
					//table of job requests of the client
					echo '<table class="soaTable" cellspacing="0" border=1px>';
					echo '<tr>';
						echo '<th  style="width:20%;">';
							echo "JR Number";
						echo '</th>';
						echo '<th  style="width:20%px;">';
							echo "Date";
						echo '</th>';
						echo '<th  style="width:40%;">';
							echo "Description";
						echo '</th>';
						echo '<th style="width:20%">';
							echo "Amount";
						echo '</th>';
					echo '</tr>';
					if($sessionUserSection!="Rent to Own"){
						while($row=mysqli_fetch_assoc($result)){
							echo '<tr>';
								echo '<td style="width:20%;">';
									echo $row['jr_number'];
									$id = $row['jr_number'];
								echo '</td>';
								echo '<td style="width:20%;">';
									echo $row['date'];
								echo '</td>';
								echo '<td style="width:40%;text-align:left;">';
									echo $row['service_type'];
								echo '</td>';
								echo '<td style="width:20%;">';
									echo $row['amount'];
								echo '</td>';
							echo '</tr>';
							$total = $total + $row['amount'];
							$count++;
						}
					}else{
						//--------------RENT TO OWN---------///
						$row=mysqli_fetch_assoc($result);
						echo '<tr>';
							echo '<td style="width:20%;">';
								echo $row['jr_number'];
								$id = $row['jr_number'];
							echo '</td>';
							echo '<td style="width:20%;">';
								echo $row['date'];
							echo '</td>';
							echo '<td style="width:40%;text-align:left;">';
								echo "Monthly Payment";
							echo '</td>';
							echo '<td style="width:20%;">';
								echo $row['monthly_payment'];
							echo '</td>';
						echo '</tr>';
						if($id!=NULL){
							$total = $row['monthly_payment'];
							$count=1;
						}
					}
					echo '</table>';
					
					echo '<br/>';
					echo '<fieldset>';
					echo '<legend>Details</legend>';
						echo '<table>';
							echo '<col width="150">';
							echo '<tr>';
								echo '<td>';
									echo "Quantity: ";
								echo '</td>';
								echo '<td>';
									echo $count;
								echo '</td>';
							echo '</tr>';
							echo '<tr>';
								echo '<td>';
									echo "Total Amount: ";
								echo '</td>';
								echo '<td>';
									echo $total;
								echo '</td>';
							echo '</tr>';
						echo '</table>';
					echo '</fieldset>';
					echo '<br/>';
					
					$stmt = "SELECT payment_status FROM soa
							WHERE soa_number=$soaToBeEdited;";

					$result = mysqli_query($con,$stmt);
					$a = mysqli_fetch_array($result);
					
					//form for updating the SOA
					echo '<div id="paymentOptions">';
						echo '<form method="post" action="" >';
							
							echo '<fieldset style="width:50%;">';
								echo '<legend>Payment Status</legend>';
								if($a[0]=="Unpaid"){
									echo '<input type="radio" checked onchange="showHideDetails(this.value)" name = "paymentStatus" value="Unpaid"/>Unpaid';
									echo '<input type="radio" onchange="showHideDetails(this.value)" name = "paymentStatus" value="Paid"/>Paid<br/>';
									$ORNum = NULL;
									$CheckNum = NULL;
									$DatePaid = NULL;
								}else{
									$stmt = "SELECT or_number,check_number,date_paid as date FROM soa
											WHERE soa_number=$soaToBeEdited;";
									$result = mysqli_query($con,$stmt);
									while($row=mysqli_fetch_assoc($result)){
										$ORNum=$row['or_number'];
										$CheckNum=$row['check_number'];
										$DatePaid=$row['date'];
									}
									echo '<input type="radio" onchange="showHideDetails(this.value)" name = "paymentStatus" value="Unpaid"/>Unpaid';
									echo '<input type="radio" checked onchange="showHideDetails(this.value)" name = "paymentStatus" value="Paid"/>Paid<br/>';
								}
							echo '</fieldset>';
							echo '<br/>';
							
								//if Unpaid is already checked
								if($a[0]=="Unpaid")
									echo '<div id="paidDetails" style="display:none;">';
								else echo '<div id="paidDetails">';
									echo '<table class="soaTable" border=1px cellspacing="0" style="text-align:left; width:40%">';
				
										echo '<tr>';
											echo '<td>';
												echo 'Date Paid';
											echo '</td>';
											echo '<td>';
												if($DatePaid==NULL){
													$stmt = "SELECT current_date as date;";
													$result = mysqli_query($con,$stmt);
													$a = mysqli_fetch_array($result);
													$DatePaid = $a[0];
												}
												echo '<input type="date"  value="'.$DatePaid.'" width="200px" id="datePaid" name = "datePaid"/><br/>';
											echo '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td>';
												echo 'OR Number';
											echo '</td>';
											echo '<td>';
												echo '<input type="text"  value="'.$ORNum.'" width="200px" id="orNum" name = "orNum"/><br/>';
											echo '</td>';
										echo '</tr>';
										echo '<tr>';
											echo '<td>';
												echo 'Check Number';
											echo '</td>';
											echo '<td>';
												echo '<input type="text"  value="'.$CheckNum.'" width="200px"  name = "checkNum" id = "checkNum"/>';
											echo '</td>';
										echo '</tr>';
									echo '</table>';
								echo '</div>';
								echo '<br/>';
							echo '</fieldset>';
							echo '<input type="submit" id="save" name = "'.$soaToBeEdited.'Save" value="Save" style="width:100px">';
							
						echo '</form>';
				
							//print button
							echo '<input type="button" id="print" name = "print" value="Print" style="width:100px" onclick="hidePrintView()">';
			}else echo '<input type="button" id="print" name = "print" value="Print" style="width:100px" onclick="hidePrint()">';				
						//back button
						echo '<form method="post" action="#top">';
							echo '<input type="submit" id="back" name = "back" value="Back" style="width:100px">';
						echo '</form>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
			
		}
		
		if($search==1){
			$stmt = "SELECT soa_number, soa_main_number, or_number, client_office, client_name, total_amount, payment_status FROM soa where soa_main_number='$soaToView';";
			$result = mysqli_query($con,$stmt);
			
			echo '<table class="soaTable" cellspacing="0" border=1px;>';
			echo '<tr>';
				echo '<tr>';
					echo '<th class="titleTable" colspan=5>';
						echo "Search Statement of Account";
					echo '</th>';
				echo '</tr>';
				echo '<th style="width:20%;">';
					echo "SOA Number";
				echo '</th>';
				echo '<th style="width:25%;">';
					echo "Client";
				echo '</th>';
				echo '<th style="width:20%;">';
					echo "Amount";
				echo '</th>';
				echo '<th style="width:20%;">';
					echo "Payment";
				echo '</th>';
				echo '<th style="width:15%;">';
					echo "Update";
				echo '</th>';
			echo '</tr>';
			$row=mysqli_fetch_assoc($result);
			if($row['soa_number']!=NULL){
				echo '<tr>';
					echo '<td  style="width:20%;">';
						echo $row['soa_main_number'];
						$soa = $row['soa_number'];
					echo '</td>';
					echo '<td  style="width:25%;">';
						if($sessionUserSection!="Rent to Own")
							echo $row['client_office'];
						else echo $row['client_name'];
					echo '</td>';
					echo '<td style="width:20%;">';
						echo $row['total_amount'];
					echo '</td>';
					echo '<td style="width:20%;">';
						echo $row['payment_status'];
					echo '</td>';
					echo '<td style="width:15%;">';
						echo '<form method="post" action="">';
							echo '<input type="submit" style="width:80px;" name="'.$soa.'" value="Update"/>';
						echo '</form>';
					echo '</td>';
				echo '</tr>';
			
				echo '</table>';
			}else{
				echo '<h2>Statement of Account not found.</h2>';
			}
			echo '<form method="post" action="">';
				echo '<input type="submit" value="Filter off" name="revert" id="revert"/>';
			echo '</form>';
		}
	?>
	</body>
</html>