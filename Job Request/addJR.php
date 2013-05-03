<?php
include("functions.php");
session_start();
session_destroy();
$admin = new databaseManager;
if(isset($_POST['addRequest'])){
	//jOB REQUEST
	$jrNumber=$_POST['telNumber'].$_POST['office'];
	$paymentStatus="1";
	$status="adasd";
	$date_created=date('Y-m-d');
	$bill_status="1";
	$client_office=$_POST['office'];
	$client_name=$_POST['name'];
	$client_email=$_POST['email'];
	$client_telnum=$_POST['telNumber'];
	$client_designation=$_POST['designation'];
	$username="samantha";
	$problem=$_POST['problem'];
	//if($_POST['problem']=="") $problem="null";
	//else $problem=$_POST['problem'];
	$date_paid="";
	$date_billed="";
	$service_type=$_POST['serviceSection'];
	if($service_type!="Rent to Own") $total_amount=$_POST['total_amount'];
	else $total_amount=$_POST['rent_total_amount'];
	$admin->addJobRequest($jrNumber, $paymentStatus, $status, $date_created, $bill_status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $username, $problem, $service_type,$total_amount);
	//$admin->addAccount($username,$password,$name,$level,$section);
	//sleep(1);
	//echo '<script>alert("Added Successfully!")</script>';
	//GENERAL -tech,sysad,netad,mis
	if($service_type!="Rent to Own"){
		$service_name=$_POST['service'];
		$details=$_POST['details'];
		//if($_POST['details']=="") $details="null";
		//else $details=$_POST['details'];
		$total_time=$_POST['total_hours'];
		$assigned_personnel=$_POST['ass_tech'];
		
		
		if($service_type!="MIS"){
			$r_materials=$_POST['material'];
			//if($_POST['material']=="") $r_materials="null";
			//else $r_materials=$_POST['material'];
			$r_comments=$_POST['comment'];
			//if($_POST['comment']=="") $r_comments="null";
			//else $r_comments=$_POST['comment'];
			$admin->addGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$r_materials,$r_comments);
			
			if($service_type=="Tech Support"){
				//tech -equipment info
				$e_brand=$_POST['brand'];
				//if($_POST['brand']=="") $e_brand="null";
				//else $e_brand=$_POST['brand'];
				$e_type=$_POST['type'];
				//if($_POST['type']=="") $e_type="null";
				//else $e_type=$_POST['type'];
				$e_par=$_POST['parno'];
				//if($_POST['parno']=="") $e_par="null";
				//else $e_par=$_POST['parno'];
				$e_accesory=$_POST['accesories'];
				//if($_POST['accesories']=="") $e_accesory="null";
				//else $e_accesory=$_POST['accesories'];
				$admin->addTech($jrNumber,$e_brand,$e_type,$e_par,$e_accesory);
			}
		
			else if($service_type=="Network Ad"){
				//net ad -table
				$i=1; //count for the table
				for($i=1;$i<=5;$i++){
					$index="equip".$i;
					$e_provided=$_POST[$index];
					if($e_provided!=""){
					$index2="serial".$i;
					$e_serial=$_POST[$index2];
					$admin->addNetAd($jrNumber,$e_provided,$e_serial);
					}
				}
			}
		}
		else{
			//MIS
			$admin->addMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel);
		}
	}
	
	else{
	//rent
	$equipment=$_POST['rent_equipment'];
	
	$monthly_payment=$_POST['rent_monthly_payment'];
	$end_of_contract=$_POST['rent_end'];
	$admin->addRent($jrNumber,$equipment,$monthly_payment,$end_of_contract);
	}
}	

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="style.css"/>
<script src="js/jquery-1.9.1.js"></script>
<script type="text/javascript">
function validateForm()
{
var x=document.forms["myForm"]["email"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
var service_type=document.forms["myForm"]["serviceSection"];
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
  alert("Invalid Email!");
  return false;
  }
  
}

		
function onchange_handler(obj, id,type) {

    var other_id;
	 $("select#service").find("option").each(function() {
                if ($(this).attr("rel") == type) {
                    $(this).removeAttr("disabled");
						  $(this).css("display","block");
                } else {
							$(this).css("display","none");
                    $(this).attr("disabled","disabled");
                }
            });
	 if(id=="tech"){
		if(obj.checked) {
        document.getElementById('tech').style.display = 'block';
		  document.getElementById('general').style.display = 'block';
		  document.getElementById('mis').style.display = 'block';
        document.getElementById('rent').style.display = 'none';
		  document.getElementById('table').style.display = 'none';
		}
	 }
	 else if(id=="sysad"){
		if(obj.checked) {
		  document.getElementById('mis').style.display = 'block';
		  document.getElementById('general').style.display = 'block';
		  document.getElementById('table').style.display = 'none';
        document.getElementById('rent').style.display = 'none';
		  document.getElementById('tech').style.display = 'none';
		  
		}
	 }
	 else if(id=="netad"){
		if(obj.checked) {
		document.getElementById('mis').style.display = 'block';
		document.getElementById('table').style.display = 'block';
		  document.getElementById('general').style.display = 'block';
        document.getElementById('rent').style.display = 'none';
			document.getElementById('tech').style.display = 'none';
		}
	 }
	 else if(id=="mis"){
		if(obj.checked) {
		  document.getElementById('table').style.display = 'none';
        document.getElementById('general').style.display = 'none';
		  document.getElementById('mis').style.display = 'block';
        document.getElementById('rent').style.display = 'none';
		  document.getElementById('tech').style.display = 'none';
		}
	 }
	 else {
		  document.getElementById('table').style.display = 'none';
        document.getElementById('rent').style.display = 'block';
		  document.getElementById('general').style.display = 'none';
		  document.getElementById('tech').style.display = 'none';
		  document.getElementById('mis').style.display = 'none';
    }
}

</script>
</head>
<body>
<form name="myForm" method="post" action="" onsubmit="return validateForm();">
						<div class="all">
						<fieldset>
							<legend>CLIENT INFO: </legend>
						<table>
							<tr> 
								<td>Name: </td>
								<td> <input type="text" name="name" size="60" placeholder="Last, Given, Middle" required/> </td>
							</tr>
							<tr>
								<td>Office/Unit: </td>
								<td> <input type="text" name="office" size="60" placeholder="Company Name" required/> </td>
								<td>Designation: </td>
								<td> <input type="text" name="designation" size="55" placeholder="None" /> </td>
							</tr>
							<tr> 
								<td>Email: </td>
								<td> <input type="email" name="email" size="60" placeholder="Email Address" required/> </td>
							
								<td>Tel. No.: </td>
								<td> <input type="text" name="telNumber" size="55" placeholder="Landline/Cellphone" required/> </td>
							</tr>
							
							<tr> 
								<td>Problem: </td>
								<td colspan="3"><textarea name="problem" rows="8" cols="100" placeholder="Please provide if there is any... "></textarea></td>
							</tr>
							</table>
							</fieldset>
							<br/>
							<br/>
							
							<fieldset id="servicesect">
							Service Section:
							</fieldset>
							<fieldset id="buttons">
									<!--<input type="radio" name="initial" checked="true" value="intial" onchange="onchange_handler(this, 'initial',this.value);" onmouseup="onchange_handler(this, 'initial',this.value);" style="display:none;">-->
									<input type="radio" name="serviceSection"  value="Tech Support" id="Tech Suppport" onchange="onchange_handler(this, 'tech',this.value);" onmouseup="onchange_handler(this, 'tech',this.value);"/> Tech Support
									<input type="radio" name="serviceSection"  value="System Ad" id="System Ad" onchange="onchange_handler(this, 'sysad',this.value);" onmouseup="onchange_handler(this, 'sysad',this.value);"/> System Ad
									<input type="radio" name="serviceSection"  value="Network Ad" id="Network Ad" onchange="onchange_handler(this, 'netad',this.value);" onmouseup="onchange_handler(this, 'netad');"/> Network Ad
									<input type="radio" name="serviceSection"  value="Rent to Own" id="Rent to own" onchange="onchange_handler(this, 'rent',this.value);" onmouseup="onchange_handler(this, 'rent',this.value);"/> Rent to own
									<input type="radio" name="serviceSection"  value="MIS" id="MIS" onchange="onchange_handler(this, 'mis',this.value);" onmouseup="onchange_handler(this, 'mis',this.value);"/> MIS
			<br/>
							</fieldset>
							</div>
							<div id="tech" style="display:none">
							<br/>
							<br/>
							<fieldset>
								<legend>EQUIPMENT INFO: </legend>
								<table>
									<tr>
										<td>Brand/Model: </td>
										<td colspan="3"><input type="text" name="brand" size="150"/> </td>
									</tr>
									<tr>
										<td>Type:</td>
										<td><input type="text" name="type" size="60"/> </td>
										<td>Par No.</td>
										<td><input type="text" name="parno" size="60" /> </td>
									</tr>
									<tr>
										<td>Accesories</td>
										<td colspan="3"><textarea name="accesories" rows="5" cols="100"></textarea></td>
									</tr>
								</table>
							</fieldset>
							<br/>
							<br/>
							</div>
							<div id="mis" style="display:none">
							<fieldset>
								<legend>SERVICE INFO: </legend>
							<table>
							<tr> 
								<td>Service: </td>
								<td>
									<select name="service" id="service">
										<option value="0" disabled selected style='display:none;'>Please Choose...</option>
										<option rel="Tech Support" value="IT Equipment Repairs and Software Installation"> IT Equipment Repairs and Software Installation</option>
										<option rel="Network Ad" value="Network Installation, Configuration and Testing">Network Installation, Configuration and Testing</option>
										<option rel="Rent to Own" value="Rent to Own Computer Package">Rent to Own Computer Package </option>
										<option rel="Network Ad" value="Wired LAN access">Wired LAN access</option>
										<option rel="System Ad" value="VoIP"> VoIP</option>
										<option rel="MIS" value="Data Administration/Data Standardization">Data Administration/Data Standardization</option>
										<option rel="System Ad" value="Web Hosting">Web Hosting</option>
										<option rel="System Ad" value="Live Streaming">Live Streaming</option>
										<option rel="Network Ad" value="Video Conferencing">Video Conferencing</option>
										<option rel="System Ad" value="E-mail Account Set-up">E-mail Account Set-up</option>
										<option rel="System Ad" value="Mailing List/E-memo">Mailing List/E-memo</option>
										<option rel="Network Ad" value="Wifi access configuration for UPLB students, faculty and staff">Wifi access configuration for UPLB students, faculty and staff</option>
										<option rel="Tech Support" value="Maintenance Service">Maintenance Service</option>
										<option rel="Tech Support" value="Purchase Request Approval for IT equipment, peripherals and supplies">Purchase Request Approval for IT equipment, peripherals and supplies</option>
										<option rel="Tech Support" value="BAC evaluation for IT equipment, peripherals and supplies">BAC evaluation for IT equipment, peripherals and supplies</option>
										<option rel="Tech Support" value="Inspection for newly acquired IT equipment, parts, peripherals and supplies">Inspection for newly acquired IT equipment, parts, peripherals and supplies</option>
										<option rel="MIS" value="System Analysis and Design">System Analysis and Design</option>
										<option rel="MIS" value="Software Review and Diagnostic">Software Review and Diagnostic</option>
										<option rel="MIS" value="Custom Software Development">Custom Software Development</option>
										<option rel="MIS" value="Database Mangement(Back-end Programming)">Database Mangement(Back-end Programming)</option>
										<option rel="MIS" value="Software/Database Maintenance">Software/Database Maintenance</option>
										<option rel="MIS" value="Website Development: Static">Website Development: Static</option>
										<option rel="MIS" value="Website Development: Dynamic">Website Development: Dynamic</option>
										<option rel="MIS" value="Custom Web Application Development">Custom Web Application Development</option>
										<option rel="MIS" value="Website Maintenance">Website Maintenance</option>
									</select>
								</td>
							</tr>
							<tr> 
								<td>Details: </td>
								<td><textarea name="details" rows="8" cols="100" placeholder="Enter details here... "></textarea></td>
							</tr>
							<tr>
										<td>Assigned Technician: </td>
										<td><input type="text" name="ass_tech" size="60" /></td>
									</tr>
									<tr>
										<td>Total number of hours: </td>
										<td><input type="text" name="total_hours" size="60" /></td>
									</tr>
									<tr>
										<td>Total amount: </td>
										<td><input type="text" name="total_amount" size="60" /></td>
									</tr>
							</table>
							</fieldset>
							<br/>
							<br/>
							</div>
							
						<div id="general" style="display:none">
							
							<fieldset>
						<legend>RECOMMENDATIONS </legend>
						<h3>Materials/Equipments</h3>
						<textarea name="material" rows="5" cols="100"></textarea>
						<br/>
						<h3>Comments</h3>
						<textarea name="comment" rows="5" cols="100"></textarea>
						</fieldset>
						<br/>
						<br/>
						</div>
						<div id="table" style="display:none">
						<br/>
							<table border="1" >
							<tr>
								<th> EQUIPMENTS PROVIDED/STATIONED</th>
								<th> SERIAL NUMBER/REMARKS </th>
							</tr>
							<tr>
								<td> <input type="text" name="equip1" size="90"/> </td>
								<td> <input type="text" name="serial1" size="85"/> </td>
							</tr>
							<tr>
								<td> <input type="text" name="equip2" size="90"/> </td>
								<td> <input type="text" name="serial2" size="85"/> </td>
							</tr>
							<tr>
								<td> <input type="text" name="equip3" size="90"/> </td>
								<td> <input type="text" name="serial3" size="85"/> </td>
							</tr>
							<tr>
								<td> <input type="text" name="equip4" size="90"/> </td>
								<td> <input type="text" name="serial4" size="85"/> </td>
							</tr>
							<tr>
								<td> <input type="text" name="equip5" size="90"/> </td>
								<td> <input type="text" name="serial5" size="85"/> </td>
							</tr>
						</table>
						<br/>
						<br/>
						</div>
						
						<div id="rent" style="display:none">
						<br/>
						<br/>
							<fieldset>
								<legend>DETAILS </legend>
								<table>
									<tr>
										<td>Equipment: </td>
										<td><input type="text" name="rent_equipment" size="60" /></td>
									</tr>
									<tr>
										<td>Total Amount: </td>
										<td><input type="text" name="rent_total_amount" size="60" /></td>
									</tr>
									<tr>
										<td>Monthly Payment: </td>
										<td><input type="text" name="rent_monthly_payment" size="60" /></td>
									</tr>
									<tr>
										<td>End of Contract: </td>
										<td><input type="date" name="rent_end" ></td>
									</tr>
								</table>
							</fieldset>
							<br/>
							<br/>
						</div>
						
						<br/>
						<!--</form>
						<form action="">-->
						<input type="submit" value="Submit" id="addRequest"
						name="addRequest"/>
						<br/>
						</form>
				
				
</body>
</html>