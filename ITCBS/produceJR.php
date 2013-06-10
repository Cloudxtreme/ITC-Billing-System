<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	$admin = new databaseManager;
	$user = new functionalityManager;
	
	if(isset($_POST['addRequest'])){
		$lastNum = $user->retrieveMaxJRNum();
		if($lastNum==NULL){
			$lastNum=100;
		}
		else{
			$lastNum=$lastNum+=1;
		}
		$srvce_sect="";
		if(isset($_POST['serviceSection'])){
			if($_POST['serviceSection']=="System Ad"){
				$srvce_sect="SYS";
			}
			else if($_POST['serviceSection']=="Network Ad"){
				$srvce_sect="NET";
			}
			else if($_POST['serviceSection']=="MIS"){
				$srvce_sect="MIS";
			}	
			else if($_POST['serviceSection']=="Rent to Own"){
				$srvce_sect="RTO";
			}
			else if($_POST['serviceSection']=="Tech Support"){
				$srvce_sect="TCS";
			}
		}
		
		$timezone = "Asia/Manila";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		$jrNumber=date("Y").'-'.$srvce_sect.'-'.$lastNum;
		$section=$sessionUserSection;
		if(!isset($_SESSION['jrNum'])){
			$_SESSION['jrNum']=$jrNumber;
		}
		
		$date_created=date('Y-m-d');
		$date_accomplished=null;
		$date_paid=null;
		$date_billed=null;
		
		$client_office=$_POST['office'];
		$client_name=$_POST['name'];
		$client_email=$_POST['email'];
		$client_telnum=$_POST['telNumber'];
		$client_designation=$_POST['designation'];
		
		$paymentStatus="Unpaid";
		$status="In Process";
		$bill_status="Unbilled";
		
		$ua_username=$sessionUsername;
		$soa_number = null;
		
		if(isset($_POST['total_amount'])){
			$total_amount=$_POST['total_amount'];
		}
		
		$problem=$_POST['problem'];
		
		$service_section=$_POST['serviceSection'];
		$service_type="";
		if(isset($_POST['service'])){
			$service_type=$_POST['service'];
		}
		if($sessionUserSection=="Rent to Own")
			$service_type="Monthly Payment";
		$user->addJobRequest($lastNum, $jrNumber, $service_section, $date_created, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem, $service_type, $status, $bill_status, $paymentStatus, $date_accomplished, $date_billed, $date_paid, $ua_username, $soa_number, $total_amount);
		if($service_section!="Rent to Own"){
			$service="";
			if(isset($_POST['service'])){
				$service_type=$_POST['serviceSection'];
			}
			$details=$_POST['details'];
			$total_time=$_POST['total_hours'];
			$assigned_personnel=$_POST['ass_tech'];
			$total_amount=$_POST['total_amount'];
			
			if($service_section!="MIS"){
				$r_materials=$_POST['material'];
				$r_comments=$_POST['comment'];
				$user->addGeneral($jrNumber,$service_type,$details,$total_time,$assigned_personnel,$total_amount,$r_materials,$r_comments);
				
				if($service_section=="Tech Support"){
					$e_newold=$_POST['newOld'];
					$e_brand=$_POST['brand'];
					$e_type=$_POST['type'];
					$e_par=$_POST['parno'];
					$e_accesory=$_POST['accesories'];
					$released_status="Not Released";
					$date_released="";
					$releasedBy="";
					$user->addTech($jrNumber,$e_newold,$e_brand,$e_type,$e_par,$e_accesory,$released_status);
				}
			
				else if($service_section=="Network Ad"){
					$e_provided1=null;
					$e_serial1=null;
					$e_provided2=null;
					$e_serial2=null;
					$e_provided3=null;
					$e_serial3=null;
					$e_provided4=null;
					$e_serial4=null;
					$e_provided5=null;
					$e_serial5=null;
					
					
					$e_provided1 = $_POST["equip1"];
					$e_serial1 = $_POST["serial1"];
				
					$e_provided2 = $_POST["equip2"];
					$e_serial2 = $_POST["serial2"];
				
					$e_provided3 = $_POST["equip3"];
					$e_serial3 = $_POST["serial3"];
			
					$e_provided4 = $_POST["equip4"];
					$e_serial4 = $_POST["serial4"];
			
					$e_provided5 = $_POST["equip5"];
					$e_serial5 = $_POST["serial5"];
				
					
					$user->addNetAd($jrNumber,$e_provided1,$e_serial1,$e_provided2,$e_serial2,$e_provided3,$e_serial3,$e_provided4,$e_serial4,$e_provided5,$e_serial5);
				}
			}
			else{
				$user->addMIS($jrNumber,$service_type,$details,$total_time,$assigned_personnel,$total_amount);
			}
		}
		else{
			$equipment=$_POST['rent_equipment'];
			$total_amount=$_POST['rent_total_amount'];
			$terms = $_POST['rent_terms'];
			$monthly_payment=$_POST['rent_monthly_payment'];
			$start_of_contract=$_POST['rent_start'];
			$end_of_contract=$_POST['rent_end'];
			$user->addRent($jrNumber,$equipment,$total_amount,$terms,$monthly_payment,$start_of_contract,$end_of_contract);
			if($terms=="12 months")
				$num = 12;
			else $num = 6;
			for($i=1 ; $i<=$num ; $i++){
				$jrNumberMonthly = $jrNumber."-".$i."/".$num;
				$user->addRentMonthly($jrNumberMonthly,$jrNumber,$client_name,$monthly_payment,$start_of_contract,$bill_status,$paymentStatus,$i);
			}
		}
		echo '<script>alert("Added Successfully!")</script>';
		//header('Location: produceJR.php');
	}
	
	//viewing for printing after adding
	if(isset($_POST['serviceSection'])){
	
		if($_POST['serviceSection']=="System Ad"){
			$srvce_sect="SYS";
		}
		else if($_POST['serviceSection']=="Network Ad"){
			$srvce_sect="NET";
		}
		else if($_POST['serviceSection']=="MIS"){
			$srvce_sect="MIS";
		}	
		else if($_POST['serviceSection']=="Rent to Own"){
			$srvce_sect="RTO";
		}
		else if($_POST['serviceSection']=="Tech Support"){
			$srvce_sect="TCS";
		}
		$jrNumber=date("Y").'-'.$srvce_sect.'-'.$lastNum;
		$values=$user->getJobRequest($jrNumber);

		if($values['SECTION']!="Rent to Own") $gen=$user->getGeneral($jrNumber);
		if($values['SECTION']=="Tech Support") $tech=$user->getTech($jrNumber);
		else if($values['SECTION']=="Rent to Own") $rent=$user->getRent($jrNumber);
		else if($values['SECTION']=="Network Ad") $net=$user->getNetad($jrNumber);
		
		$date_created=date('Y-m-d');

		$date_accomplished=null;
		$date_paid=null;
		$date_billed=null;

		$client_office=$_POST['office'];
		$client_name=$_POST['name'];
		$client_email=$_POST['email'];
		$client_telnum=$_POST['telNumber'];
		$client_designation=$_POST['designation'];

		$paymentStatus=0;
		$status="In Process";
		$bill_status=0;

		$ua_username=$sessionUsername;
		$problem=$_POST['problem'];
		$service_section=$_POST['serviceSection'];
		if(isset($_POST['service'])){
			$service_type=$_POST['service'];
		}
		
		$details=$_POST['details'];
		$total_time=$_POST['total_hours'];
		$assigned_personnel=$_POST['ass_tech'];
		$total_amount=$_POST['total_amount'];
		$r_materials=$_POST['material'];
		$r_comments=$_POST['comment'];
		$e_newold=$_POST['newOld'];
		$e_brand=$_POST['brand'];
		$e_type=$_POST['type'];
		$e_par=$_POST['parno'];
		$e_accesory=$_POST['accesories'];

		$e_provided1 = $_POST["equip1"];
		$e_serial1 = $_POST["serial1"];

		$e_provided2 = $_POST["equip2"];
		$e_serial2 = $_POST["serial2"];

		$e_provided3 = $_POST["equip3"];
		$e_serial3 = $_POST["serial3"];

		$e_provided4 = $_POST["equip4"];
		$e_serial4 = $_POST["serial4"];

		$e_provided5 = $_POST["equip5"];
		$e_serial5 = $_POST["serial5"];

		$equipment=$_POST['rent_equipment'];
		$rent_total_amount=$_POST['rent_total_amount'];
		$terms = $_POST['rent_terms'];
		$monthly_payment=$_POST['rent_monthly_payment'];
		$start_of_contract=$_POST['rent_start'];
		$end_of_contract=$_POST['rent_end'];
		
	}
	//viewing after clicking view button
	else {
		$count=$user->countJR($sessionUserSection);
		if($sessionUserType=="Executive")
			$count=$user->countJRAll();
		for($i=0;$i<$count;$i++){
			$index="view".$i;
			if(isset($_POST[$index])){
				$index2="jrNum".$i;
				$values=$user->getJobRequest($_POST[$index2]);
				$jrNum=$_POST[$index2];
				if($values['SECTION']!="Rent to Own") $gen=$user->getGeneral($_POST[$index2]);
				if($values['SECTION']=="Tech Support") $tech=$user->getTech($_POST[$index2]);
				else if($values['SECTION']=="Rent to Own") $rent=$user->getRent($_POST[$index2]);
				else if($values['SECTION']=="Network Ad") $net=$user->getNetad($_POST[$index2]);
				
				$jrNumber=$values['JR_NUMBER'];
				$date_released=$user->dateReleased($jrNumber);
				$releasedBy=$user->releasedBy($jrNumber);
				$date_created=$values['DATE_CREATED'];
				$client_office=$values['CLIENT_OFFICE'];
				$client_name=$values['CLIENT_NAME'];
				$client_email=$values['CLIENT_EMAIL'];
				$client_telnum=$values['CLIENT_TELNUM'];
				$client_designation=$values['CLIENT_DESIGNATION'];
				
				$problem=$values['PROBLEM'];
				
				$service_section=$values['SECTION'];
				$ua_username=$sessionUsername;
				$service_type=$values['SERVICE_TYPE'];
				$details="";
				
				$total_time="";
				$assigned_personnel="";
				$total_amount="";
				$r_materials="";
				$r_comments="";	
				if($values['SECTION']!="Rent to Own"){
					$details=$gen['DETAILS'];
					
					$total_time=$gen['TOTAL_TIME'];
					$assigned_personnel=$gen['ASSIGNED_PERSONNEL'];
					$total_amount=$gen['TOTAL_AMOUNT'];
					$r_materials=$gen['R_MATERIALS'];
					$r_comments=$gen['R_COMMENTS'];	
				}
				$e_brand="";
				$e_type="";
				$e_par="";
				$e_accesory="";
				
				if($values['SECTION']=="Tech Support") {
					$e_newold=$tech['NEWOLD'];
					$e_brand=$tech['E_BRAND'];
					$e_type=$tech['E_TYPE'];
					$e_par=$tech['E_PAR'];
					$e_accesory=$tech['E_ACCESORY'];
				}
				
				$e_provided1 = "";
				$e_serial1 = "";

				$e_provided2 = "";
				$e_serial2 = "";

				$e_provided3 = "";
				$e_serial3 = "";

				$e_provided4 = "";
				$e_serial4 = "";

				$e_provided5 = "";
				$e_serial5 = "";
				
				if($values['SECTION']=="Network Ad") {
					$e_provided1 = $net["EQUIP1"];
					$e_serial1 = $net["SERIAL1"];

					$e_provided2 = $net["EQUIP2"];
					$e_serial2 = $net["SERIAL2"];

					$e_provided3 = $net["EQUIP3"];
					$e_serial3 = $net["SERIAL3"];

					$e_provided4 = $net["EQUIP4"];
					$e_serial4 = $net["SERIAL4"];

					$e_provided5 = $net["EQUIP5"];
					$e_serial5 = $net["SERIAL5"];
				}
				$equipment="";
				$rent_total_amount="";
				$monthly_payment="";
				$end_of_contract="";
				
				if($values['SECTION']=="Rent to Own") {
					$equipment=$rent['EQUIPMENT'];
					$rent_total_amount=$rent['TOTAL_AMOUNT'];
					$terms = $rent['TERMS'];
					$monthly_payment=$rent['MONTHLY_PAYMENT'];
					$start_of_contract=$rent['START_OF_CONTRACT'];
					$end_of_contract=$rent['END_OF_CONTRACT'];
				}
				break;
			}
		}
	}
	
?>	
<!-- PRODUCE A COPY OF JOB REQUEST THAT IS READY TO PRINT (CAN'T BE EDITED; JUST FOR VIEWING PURPOSE)-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<script src="js/jquery-1.9.1.js"></script>
		<script>
			function printpage(){
				  document.getElementById('print').style.display = 'none';
				  document.getElementById('back').style.display = 'none';
				  window.print()
				  document.getElementById('print').style.display = 'block';
				  document.getElementById('back').style.display = 'block';
			}
		</script>
		<style>
			input::-webkit-input-placeholder { /* WebKit browsers */
				color:#000;
			}
			:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
				color:#000;
			}
			::-moz-placeholder { /* Mozilla Firefox 19+ */
				color:#000;
			}
			:-ms-input-placeholder { /* Internet Explorer 10+ */
				color:#000;
			}

			@media print {
			.header, .hide { visibility: hidden }
			.footer, .hide { visibility: hidden }
			}
		</style>
	</head>

	<body>
		<div id="printVersion">
		<table>
			<col width="100">
			<col width="720">
			<col width="200">
			<tr>
				<td>
					<img src="ITClogo.jpg"  style="width:100px; height:100px;"/>
				</td>
				<td>
					<p>
						<b>
							ULPB - ITC<br/>
							University of the Philippines Los Banos<br/>
							<i>Service Request Form</i><br/>
						</b>
						VOIP #105<br/>
						Tel No. 501-4793
					</p>
				</td>
				<td>
					<table>
						<tr>
							<td colspan="2">
								<u><i>Accomplish in Duplicate</u></i>
							</td>
						</tr>
						<tr>
							<td><p><b>No. :</b></p></td>
							<td><input type="text" name="ino" size="20" placeholder="<?php echo $jrNumber;?>" readonly="readonly" /></td>
						</tr>
						<tr>
							<td><p><b>Date:</b></p></td>
							<td><input type="text" name="jrdate" size="20" placeholder="<?php echo $date_created; ?>" readonly="readonly" /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div class="all">
			<fieldset>
				<legend class="print">CLIENT INFO: </legend>
				<table>
					<tr> 
						<td>Name: </td>
						<td> <input type="text" name="name" size="45" placeholder="<?php echo $client_name; ?>" readonly="readonly" /> </td>
					<!--</tr>
					<tr>-->
						<td>Designation: </td>
						<td> <input type="text" name="designation" size="45" placeholder="<?php echo $client_designation; ?>" style="color:black;" readonly="readonly" /> </td>
						</tr><tr>
						<td>Office/Unit: </td>
						<td colspan="3"> <input type="text" name="office" size="107" placeholder="<?php echo $client_office; ?>" style="color:black;" readonly="readonly" /> </td>
						
					</tr>
					<tr> 
						<td>Email: </td>
						<td> <input type="email" name="email" size="45" placeholder="<?php echo $client_email; ?>" style="color:black;" readonly="readonly" /> </td>
						<!--</tr><tr>-->
						<td>Tel. No.: </td>
						<td> <input type="text" name="telNumber" size="45" placeholder="<?php echo $client_telnum; ?>" style="color:black;" readonly="readonly" /> </td>
					</tr>
					<!--<tr> 
						<td>Problem: </td>
						<td colspan="3"><textarea name="problem" rows="8" cols="60" style="color:black;" readonly="readonly" ><?php echo $problem; ?> </textarea></td>
					</tr>-->
				</table>
			</fieldset><br/>
			<!--<fieldset id="servicesect">
				Service Section:
			</fieldset>-->
			
			<fieldset id="buttons" <?php if($service_section=="Tech Support"){ ?> style="display:none" <?php } ?>>
				<legend class="print">SERVICE SECTION: </legend>
				<input type="radio" name="serviceSection"  <?php if($service_section=="Tech Support"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Tech Support
				<input type="radio" name="serviceSection"  value="System Ad" id="System Ad" <?php if($service_section=="System Ad"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> System Ad
				<input type="radio" name="serviceSection"  value="Network Ad" id="Network Ad"  <?php if($service_section=="Network Ad"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Network Ad
				<input type="radio" name="serviceSection"  value="Rent to Own" id="Rent to own"  <?php if($service_section=="Rent to Own"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Rent to own
				<input type="radio" name="serviceSection"  value="MIS" id="MIS" <?php if($service_section=="MIS"){ ?> checked="true"<?php } else { ?>disabled <?php } ?> /> MIS
			</fieldset>
			
		</div>
		<div id="tech" <?php if($sessionUserSection=="Tech Support"){ echo" style='display:block'; "; }else{ echo "style='display:none';";}?>>
			<fieldset>
				<legend class="print">EQUIPMENT INFO: </legend>
				<table>
					<tr>
							<td>New/Old: </td>
							<td><input type="text" name="newold" size="40" placeholder="<?php echo $e_newold; ?>" style="color:black;" readonly="readonly" /> </td>
						<td>Brand/Model: </td>
						<td><input type="text" name="brand" size="40" placeholder="<?php echo $e_brand; ?>" style="color:black;" readonly="readonly" /> </td>
					</tr>
					<tr>
						<td>Type:</td>
						<td><input type="text" name="type" size="40" placeholder="<?php echo $e_type; ?>" style="color:black;" readonly="readonly" /> </td>
						<td>Par No.</td>
						<td><input type="text" name="parno" size="40" placeholder="<?php echo $e_par; ?>" style="color:black;" readonly="readonly" /> </td>
					</tr>
					<tr>
						<td>Accesories</td>
						<td colspan="3"><textarea name="accesories" rows="2" cols="82" style="color:black;" readonly="readonly"> <?php echo $e_accesory; ?></textarea></td>
					</tr>
				</table>
			</fieldset><br/>
		</div>
		<div id="mis"<?php if($sessionUserSection!="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
			<fieldset>
				<legend class="print">SERVICE INFO: </legend>
				<table>
					<tr> 
						<td>Service: </td>
						<td><input type="text" name="name" size="50"  placeholder="<?php echo $service_type; ?>" readonly="readonly" /></td>
						<td></td>
						<td></td>
					</tr>
					<tr> 
						<td>Details: </td>
						<td colspan="3"><textarea name="details" rows="2" cols="70" style="color:black;" readonly="readonly" > <?php echo $details; ?></textarea></td>
					</tr>
					<tr>
						<td>Assigned Technician: </td>
						<td colspan="3"><input type="text" name="ass_tech" size="90" placeholder="<?php echo $assigned_personnel; ?>" style="color:black;" readonly="readonly"  /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td>Total number of hours: </td>
						<td><input type="text" name="total_hours" size="50" placeholder="<?php echo $total_time; ?>" style="color:black;" readonly="readonly" /></td>
						<td>Total amount: </td>
						<td><input type="text" name="total_amount" size="20" placeholder="<?php echo $total_amount; ?>" style="color:black;" readonly="readonly" /></td>
					</tr>
				</table>
			</fieldset><br/>
		</div>
		<div id="general" <?php if($sessionUserSection=="Tech Support" || $sessionUserSection=="System Ad" || $sessionUserSection=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
			<fieldset>
				<legend class="print">RECOMMENDATIONS </legend>
				Materials/Equipments<br/>
				<textarea name="material" rows="2" cols="90" style="color:black;" readonly="readonly"> <?php echo $r_materials; ?> </textarea><br/>
				Comments<br/>
				<textarea name="comment" rows="2" cols="90" style="color:black;" readonly="readonly"> <?php echo $r_comments; ?> </textarea>
			</fieldset><br/>
		</div>
		<div id="table" <?php if($sessionUserSection=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
			<table border="1px solid" cellspacing="0">
				<tr>
					<th> EQUIPMENTS PROVIDED/STATIONED</th>
					<th> SERIAL NUMBER/REMARKS </th>
				</tr>
				<tr>
					<td>
						<input type='text' value='<?php echo $e_provided1?>' size='50' />
					</td>
					<td>
						<input type='text' value='<?php echo $e_serial1?>' size='45' />
					</td>
				</tr>
				<tr>
					<td>
						<input type='text' value='<?php echo $e_provided2?>' size='50' />
					</td>
					<td>
						<input type='text' value='<?php echo $e_serial2?>' size='45' />
					</td>
				</tr>
				<tr>
					<td>
						<input type='text' value='<?php echo $e_provided3?>' size='50' />
					</td>
					<td>
						<input type='text' value='<?php echo $e_serial3?>' size='45' />
					</td>
				</tr>
				<tr>
					<td>
						<input type='text' value='<?php echo $e_provided4?>' size='50' />
					</td>
					<td>
						<input type='text' value='<?php echo $e_serial4?>' size='45' />
					</td>
				</tr>
				<tr>
					<td>
						<input type='text' value='<?php echo $e_provided5?>' size='50' />
					</td>
					<td>
						<input type='text' value='<?php echo $e_serial5?>' size='45' />
					</td>
				</tr>
			</table><br/>
		</div>
		<div id="rent" <?php if($sessionUserSection=="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
			<fieldset>
				<legend class="print">RENT TO OWN - DETAILS </legend>
				<table>
					<tr>
						<td>Equipment: </td>
						<td><input type="text" name="rent_equipment" size="60" placeholder="<?php echo $equipment; ?>" style="color:black;" readonly="readonly" /></td>
					</tr>
					<tr>
						<td>Total Amount: </td>
						<td><input type="text" name="rent_total_amount" size="60" placeholder="<?php echo $rent_total_amount; ?>" style="color:black;" readonly="readonly" /></td>
					</tr>
					<tr>
						<td>Terms: </td>
						<td><input type="text" name="rent_terms" size="60" placeholder="<?php echo $terms; ?>" style="color:black;" readonly="readonly" /></td>
					</tr>
					<tr>
						<td>Monthly Payment: </td>
						<td><input type="text" name="rent_monthly_payment" size="60" placeholder="<?php echo $monthly_payment; ?>" style="color:black;" readonly="readonly" /></td>
					</tr>
					<tr>
						<td>Start of Contract: </td>
						<td><input type='text' placeholder="<?php echo $start_of_contract;?>" readonly='readonly' style='color:black;'/></td>
					</tr>
					<tr>
						<td>End of Contract: </td>
						<td><input type='text' placeholder="<?php echo $end_of_contract;?>" readonly='readonly' style='color:black;'/></td>
					</tr>
				</table>
			</fieldset><br/>
		</div>
		<?php if($sessionUserSection!="Tech Support"){ ?>
		<table>
			<col align="center">
			<tr>
				<td><b>End User's Acceptance: </b></td>
				<td>________________________________</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<pre>  Signature Over Printed Name</pre>
				</td>
			</tr>
			</table>
			<?php if($sessionUserSection=="MIS" || $sessionUserSection=="System Ad" || $sessionUserSection=="Network Ad"){ ?>
			<br/>
			<br/>
			<table>
			<tr>
				<td><b>Assigned Technician: </b></td>
				<td>&nbsp;&nbsp;&nbsp;  ________________________________</td>
			</tr>
			<tr>
				<td>
				</td>
				<td>
					<pre>&nbsp;&nbsp;  Signature Over Printed Name</pre>
				</td>
			</tr>
			</table>
			<?php } ?>
		
		<?php } if($sessionUserSection=="Tech Support"){ ?>
		<fieldset class="print">
			<table>
				<tr>
					<td style="font-size:14px">Assigned Technician: </td>
					<td><input type="text" name="ass_tech2" size="40" placeholder="<?php echo $assigned_personnel; ?>" style="color:black;" readonly="readonly"  /></td>
					<td  style="font-size:14px">Released By: </td>
					<td><input type="text" name="date_released" size="40" placeholder="<?php echo $releasedBy; ?>" style="color:black;" readonly="readonly"  /></td>
				</tr>
				<tr>
					<td style="font-size:14px">End User's Acceptance: </td>
					<td>_______________________</td>
					<td style="font-size:14px">Date Released</td>
					<td><input type="text" name="date_released" size="40" placeholder="<?php echo $date_released; ?>" style="color:black;" readonly="readonly"  /></td>
				</tr>
				<tr>
					<td></td>
					<td style="font-size:14px"> (End User's Signature)</td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</fieldset>
		<p style="font-size:11px">Important notice:</br>Terms and Conditions: The client shall fully understand that for any repair and/or reburbishing jobes without any initial diagnostics done by ITC Technical Support Team, such computer unit may be subjected to physical/electronic stress such that the components may break down or malfunction during the process. ITC Technical Support Team will not be responsible for the damage(s) incurred to the client's unit in such cases.
		<br/>&nbsp; * Software installation licensed CD will be provided by the client.</p>
		<?php } ?>
		</div>
		<form action="">
			<input type="button" id="print" value="PRINT" onclick="printpage()"/><br/>
		</form>
		<?php 
		if(isset($_POST['serviceSection'])){ 
			echo'<form method="post" action="addJR.php">';
				echo'<input type="submit" value="BACK" id="back" name="back"/>';
			echo'</form>';
		}
		else{ 
			if($sessionUserType=="Manager"){
				echo'<form method="post" action="job_request_manager.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';
			}
			else if($sessionUserType=="User(Encoder)"){
				echo'<form method="post" action="job_request_user.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';
			}
			else if($sessionUserType=="Executive"){
				echo'<form method="post" action="job_request_exec.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';
			}
		}
		?>
	</body>
</html>