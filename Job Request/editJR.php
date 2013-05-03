<?php
include("functions.php");
$admin = new databaseManager;
$count=$admin->countJR();
for($i=0;$i<$count;$i++){
	$index="submit".$i;
	if(isset($_POST[$index])){
		$index2="jrnum".$i;
		$values=$admin->getJobRequest($_POST[$index2]);
		$jrNum=$_POST[$index2];
		if($values['SERVICE_TYPE']!="Rent to Own") $gen=$admin->getGeneral($_POST[$index2]);
		if($values['SERVICE_TYPE']=="Tech Support") $tech=$admin->getTech($_POST[$index2]);
		else if($values['SERVICE_TYPE']=="Rent to Own") $rent=$admin->getRent($_POST[$index2]);
		else if($values['SERVICE_TYPE']=="Network Ad") $net[]=$admin->getNetad($_POST[$index2]);
		
		
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
<form name="myForm" method="post" action="viewJR.php" onsubmit="return validateForm();">
						<div class="all">
						<input type="hidden" name="jrNum" value="<?php echo $jrNum;?>">
						<input type="hidden" name="serviceSection" value="<?php echo $values['SERVICE_TYPE'];?>">
						<input type="hidden" name="origStatus" value="<?php echo $values['STATUS'];?>">
						<fieldset>
							<legend>CLIENT INFO: </legend>
						<table>
							<tr> 
								<td>Name: </td>
								<td> <input type="text" name="name" size="60" value="<?php echo $values['CLIENT_NAME'];?>"required/> </td>
							</tr>
							<tr>
								<td>Office/Unit: </td>
								<td> <input type="text" name="office" size="60" value="<?php echo $values['CLIENT_OFFICE'];?>" required/> </td>
								<td>Designation: </td>
								<td> <input type="text" name="designation" size="55" value="<?php echo $values['CLIENT_DESIGNATION'];?>"/> </td>
							</tr>
							<tr> 
								<td>Email: </td>
								<td> <input type="email" name="email" size="60" value="<?php echo $values['CLIENT_EMAIL'];?>"required/> </td>
							
								<td>Tel. No.: </td>
								<td> <input type="text" name="telNumber" size="55" value="<?php echo $values['CLIENT_TELNUM'];?>"required/> </td>
							</tr>
							
							<tr> 
								<td>Problem: </td>
								<td colspan="3"><textarea name="problem" rows="8" cols="100" placeholder="<?php if($values['PROBLEM']=="") echo "Please provide if there is any... "; else echo "";?>"><?php echo $values['PROBLEM'];?></textarea></td>
							</tr>
							</table>
							</fieldset>
							<br/>
							<br/>
							
							<!--<fieldset id="servicesect" style="display:none">
							Service Section:
							</fieldset>
							<fieldset id="buttons" style="display:none">
									<input type="radio" name="serviceSection"  value="Tech Support" id="Tech Suppport" onchange="onchange_handler(this, 'tech',this.value);" onmouseup="onchange_handler(this, 'tech',this.value);" <?php if($values['SERVICE_TYPE']=="Tech Support"){ ?> checked="true"<?php }?>/> Tech Support
									<input type="radio" name="serviceSection"  value="System Ad" id="System Ad" onchange="onchange_handler(this, 'sysad',this.value);" onmouseup="onchange_handler(this, 'sysad',this.value);" <?php if($values['SERVICE_TYPE']=="System Ad"){ ?> checked="true"<?php }?>/> System Ad
									<input type="radio" name="serviceSection"  value="Network Ad" id="Network Ad" onchange="onchange_handler(this, 'netad',this.value);" onmouseup="onchange_handler(this, 'netad');" <?php if($values['SERVICE_TYPE']=="Network Ad"){ ?> checked="true"<?php }?>/> Network Ad
									<input type="radio" name="serviceSection"  value="Rent to Own" id="Rent to own" onchange="onchange_handler(this, 'rent',this.value);" onmouseup="onchange_handler(this, 'rent',this.value);" <?php if($values['SERVICE_TYPE']=="Rent to Own"){ ?> checked="true"<?php }?>/> Rent to own
									<input type="radio" name="serviceSection"  value="MIS" id="MIS" onchange="onchange_handler(this, 'mis',this.value);" onmouseup="onchange_handler(this, 'mis',this.value);"  <?php if($values['SERVICE_TYPE']=="MIS"){ ?> checked="true"<?php }?>/> MIS
			<br/>
							</fieldset>-->
							</div>
							<div id="tech" <?php if($values['SERVICE_TYPE']=="Tech Support"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
							<br/>
							<br/>
							<fieldset>
								<legend>EQUIPMENT INFO: </legend>
								<table>
									<tr>
										<td>Brand/Model: </td>
										<td colspan="3"><input type="text" name="brand" size="150" value="<?php if($values['SERVICE_TYPE']=="Tech Support") echo $tech['E_BRAND'];?>"/> </td>
									</tr>
									<tr>
										<td>Type:</td>
										<td><input type="text" name="type" size="60" value="<?php if($values['SERVICE_TYPE']=="Tech Support") echo $tech['E_TYPE']; else echo "";?>"/> </td>
										<td>Par No.</td>
										<td><input type="text" name="parno" size="60" value="<?php if($values['SERVICE_TYPE']=="Tech Support") echo $tech['E_PAR']; else echo "";?>"/> </td>
									</tr>
									<tr>
										<td>Accesories</td>
										<td colspan="3"><textarea name="accesories" rows="5" cols="100"><?php if($values['SERVICE_TYPE']=="Tech Support") echo $tech['E_ACCESORY']; else echo "";?></textarea></td>
									</tr>
								</table>
							</fieldset>
							<br/>
							<br/>
							</div>
							<div id="mis" <?php if($values['SERVICE_TYPE']!="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
							<fieldset>
								<legend>SERVICE INFO: </legend>
							<table>
							<tr> 
								<td>Service: </td>
								<td>
									<select name="service" id="service">
										<option rel="Tech Support" value="IT Equipment Repairs and Software Installation" <?php if($values['SERVICE_TYPE']!="Tech Support"){?> disabled style='display:none;'<?php } if($gen['SERVICE_NAME']=="IT Equipment Repairs and Software Installation"){?>selected <?php } ?>> IT Equipment Repairs and Software Installation</option>
										<option rel="Network Ad" value="Network Installation, Configuration and Testing" <?php if($values['SERVICE_TYPE']!="Network Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Network Installation, Configuration and Testing"){?>selected <?php } ?>>Network Installation, Configuration and Testing</option>
										<option rel="Network Ad" value="Wired LAN access" <?php if($values['SERVICE_TYPE']!="Network Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Wired LAN access"){?>selected <?php } ?>>Wired LAN access</option>
										<option rel="System Ad" value="VoIP" <?php if($values['SERVICE_TYPE']!="System Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="VoIP"){?>selected <?php } ?>> VoIP</option>
										<option rel="MIS" value="Data Administration/Data Standardization" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Data Administration/Data Standardization"){?>selected <?php } ?>>Data Administration/Data Standardization</option>
										<option rel="System Ad" value="Web Hosting" <?php if($values['SERVICE_TYPE']!="System Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Web Hosting"){?>selected <?php } ?>>Web Hosting</option>
										<option rel="System Ad" value="Live Streaming" <?php if($values['SERVICE_TYPE']!="System Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Live Streaming"){?>selected <?php } ?>>Live Streaming</option>
										<option rel="Network Ad" value="Video Conferencing" <?php if($values['SERVICE_TYPE']!="Network Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Video Conferencing"){?>selected <?php } ?>>Video Conferencing</option>
										<option rel="System Ad" value="E-mail Account Set-up" <?php if($values['SERVICE_TYPE']!="System Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="E-mail Account Set-up"){?>selected <?php } ?>>E-mail Account Set-up</option>
										<option rel="System Ad" value="Mailing List/E-memo" <?php if($values['SERVICE_TYPE']!="System Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Mailing List/E-memo"){?>selected <?php } ?>>Mailing List/E-memo</option>
										<option rel="Network Ad" value="Wifi access configuration for UPLB students, faculty and staff" <?php if($values['SERVICE_TYPE']!="Network Ad"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Wifi access configuration for UPLB students, faculty and staff"){?>selected <?php } ?>>Wifi access configuration for UPLB students, faculty and staff</option>
										<option rel="Tech Support" value="Maintenance Service" <?php if($values['SERVICE_TYPE']!="Tech Support"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Maintenance Service"){?>selected <?php } ?>>Maintenance Service</option>
										<option rel="Tech Support" value="Purchase Request Approval for IT equipment, peripherals and supplies" <?php if($values['SERVICE_TYPE']!="Tech Support"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Purchase Request Approval for IT equipment, peripherals and supplies"){?>selected <?php } ?>>Purchase Request Approval for IT equipment, peripherals and supplies</option>
										<option rel="Tech Support" value="BAC evaluation for IT equipment, peripherals and supplies" <?php if($values['SERVICE_TYPE']!="Tech Support"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="BAC evaluation for IT equipment, peripherals and supplies"){?>selected <?php } ?>>BAC evaluation for IT equipment, peripherals and supplies</option>
										<option rel="Tech Support" value="Inspection for newly acquired IT equipment, parts, peripherals and supplies" <?php if($values['SERVICE_TYPE']!="Tech Support"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Inspection for newly acquired IT equipment, parts, peripherals and supplies"){?>selected <?php } ?>>Inspection for newly acquired IT equipment, parts, peripherals and supplies</option>
										<option rel="MIS" value="System Analysis and Design" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="System Analysis and Design"){?>selected <?php } ?>>System Analysis and Design</option>
										<option rel="MIS" value="Software Review and Diagnostic" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Software Review and Diagnostic"){?>selected <?php } ?>>Software Review and Diagnostic</option>
										<option rel="MIS" value="Custom Software Development" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Custom Software Development"){?>selected <?php } ?>>Custom Software Development</option>
										<option rel="MIS" value="Database Mangement(Back-end Programming)" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Database Mangement(Back-end Programming)"){?>selected <?php } ?>>Database Mangement(Back-end Programming)</option>
										<option rel="MIS" value="Software/Database Maintenance" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Software/Database Maintenance"){?>selected <?php } ?>>Software/Database Maintenance</option>
										<option rel="MIS" value="Website Development: Static" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Website Development: Static"){?>selected <?php } ?>>Website Development: Static</option>
										<option rel="MIS" value="Website Development: Dynamic" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Website Development: Dynamic"){?>selected <?php } ?>>Website Development: Dynamic</option>
										<option rel="MIS" value="Custom Web Application Development" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Custom Web Application Development"){?>selected <?php } ?>>Custom Web Application Development</option>
										<option rel="MIS" value="Website Maintenance" <?php if($values['SERVICE_TYPE']!="MIS"){?> disabled style='display:none;' <?php } if($gen['SERVICE_NAME']=="Website Maintenance"){?>selected <?php } ?>>Website Maintenance</option>
									</select>
								</td>
							</tr>
							<tr> 
								<td>Details: </td>
								<td><textarea name="details" rows="8" cols="100" placeholder="<?php if($gen['DETAILS']=="") echo "Enter details here... "; else echo "";?>"><?php echo $gen['DETAILS'];?></textarea></td>
							</tr>
							<tr>
										<td>Assigned Technician: </td>
										<td><input type="text" name="ass_tech" size="60"  value="<?php echo $gen['ASSIGNED_PERSONNEL'];?>"/></td>
									</tr>
									<tr>
										<td>Total number of hours: </td>
										<td><input type="text" name="total_hours" size="60" value="<?php echo $gen['TOTAL_TIME'];?>"/></td>
									</tr>
									<tr>
										<td>Total amount: </td>
										<td><input type="text" name="total_amount" size="60" value="<?php echo $gen['TOTAL_AMOUNT'];?>"/></td>
									</tr>
							</table>
							</fieldset>
							<br/>
							<br/>
							</div>
							
						<div id="general" <?php if($values['SERVICE_TYPE']=="Tech Support" || $values['SERVICE_TYPE']=="System Ad" || $values['SERVICE_TYPE']=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
							
							<fieldset>
						<legend>RECOMMENDATIONS </legend>
						<h3>Materials/Equipments</h3>
						<textarea name="material" rows="5" cols="100"><?php echo $gen['R_MATERIALS'];?></textarea>
						<br/>
						<h3>Comments</h3>
						<textarea name="comment" rows="5" cols="100"><?php echo $gen['R_COMMENTS'];?></textarea>
						</fieldset>
						<br/>
						<br/>
						</div>
						<div id="table" <?php if($values['SERVICE_TYPE']=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
						<br/>
							<table border="1" >
							<tr>
								<th> EQUIPMENTS PROVIDED/STATIONED</th>
								<th> SERIAL NUMBER/REMARKS </th>
							</tr>
							<?php for($j=0;$j<count($net);$j++){
							echo "<tr>";
							echo "<td>";
							$in=$j+1;
							echo "<input type='text' name='equip{$in}' size='90' value='{$net[$j]['E_PROVIDED']}'/>";
							echo "</td>";
							echo	"<td>";
							echo "<input type='text' name='serial{$in}' size='85' value='{$net[$j]['E_SERIAL']}'/>";
							echo "</td>";
							echo "</tr>";
							}
							if(count($net)<5){
								for($j=0;$j<5-count($net);$j++){
									echo "<tr>";
									echo "<td>";
									$in=$j+count($net)+1;
									echo"<input type='text' name='equip{$in}' size='90'/>";
									echo "</td>";
									echo	"<td>";
									echo "<input type='text' name='serial{$in}' size='85'/>"; 
									echo "</td>";
									echo "</tr>";
								}
							}?>
						</table>
						<br/>
						<br/>
						</div>
						
						<div id="rent" <?php if($values['SERVICE_TYPE']=="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
						<br/>
						<br/>
							<fieldset>
								<legend>DETAILS </legend>
								<table>
									<tr>
										<td>Equipment: </td>
										<td><input type="text" name="rent_equipment" size="60" value="<?php echo $rent['EQUIPMENT'];?>"/></td>
									</tr>
									<tr>
										<td>Total Amount: </td>
										<td><input type="text" name="rent_total_amount" size="60" value="<?php echo $rent['TOTAL_AMOUNT'];?>"/></td>
									</tr>
									<tr>
										<td>Monthly Payment: </td>
										<td><input type="text" name="rent_monthly_payment" size="60" value="<?php echo $rent['MONTHLY_PAYMENT'];?>"/></td>
									</tr>
									<tr>
										<td>End of Contract: </td>
										<td><input type="date" name="rent_end" value="<?php echo $rent['END_OF_CONTRACT'];?>"></td>
									</tr>
								</table>
							</fieldset>
							<br/>
							<br/>
						</div>
						<div class="all">
							<fieldset id="status">
							<legend>Status:</legend>
							
							<fieldset id="buttons">
									<input type="radio" name="status"  value="In Process" checked="true" checked="true"/> In Process
									<input type="radio" name="status"  value="Pending" /> Pending
									<input type="radio" name="status"  value="Done" /> Done
									<input type="radio" name="status"  value="Cancelled" /> Cancelled
			<br/>
							</fieldset>
							
							<br/>
							<br/>
							<fieldset>
							<legend> Reason/s</legend>
							<textarea name="reason" rows="5" cols="100" placeholder="Required"></textarea>
							</fieldset>
							</fieldset>
							<br/>
							<br/>
							<!--<fieldset id="status">
							Bill Status:
							</fieldset>
							<fieldset id="buttons">
									<input type="radio" name="billstatus"  value="1" checked="true"/> Unbilled
									<input type="radio" name="billstatus"  value="2" /> Billed
			<br/>
							</fieldset>
							$TIME=TIMESTAMP();
							<br/>
							<br/>
							<fieldset id="status">
							Payment Status:
							</fieldset>
							<fieldset id="buttons">
									<input type="radio" name="paymentstatus"  value="1" checked="true"/> Unpaid
									<input type="radio" name="paymentstatus"  value="2" /> Paid
			<br/>
							</fieldset>
							
							<br/>
							<br/>-->
							<fieldset>
							<legend> Comment/s</legend>
							<textarea name="comment2" rows="5" cols="100" placeholder="Recommended"></textarea>
							</fieldset>
							<br/>
							<br/>
							</div>
						<br/>
						<!--</form>
						<form action="">-->
						<input type="submit" value="Submit" id="editRequest"
						name="editRequest"/>
						<br/>
						</form>
				
				
</body>
</html>