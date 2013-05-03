<?php
include("connect.php");
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
	 else if(id=="initial"){
		if(obj.checked) {
		  document.getElementById('table').style.display = 'none';
        document.getElementById('general').style.display = 'none';
		  document.getElementById('mis').style.display = 'none';
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
<form name="myForm" method="post" action="jrform.php" onsubmit="return validateForm();">
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
								<td> <input type="text" name="designation" size="55" placeholder="None" required/> </td>
							</tr>
							<tr> 
								<td>Email: </td>
								<td> <input type="email" name="email" size="60" placeholder="Email Address"/> </td>
							
								<td>Tel. No.: </td>
								<td> <input type="text" name="telNumber" size="55" placeholder="Landline/Cellphone"/> </td>
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
										<option rel="Network Ad" value="">Network Installation, Configuration and Testing">Network Installation, Configuration and Testing</option>
										<option rel="Rent to Own" value="Rent to Own Computer Package">Rent to Own Computer Package </option>
										<option rel="Network Ad" value="Wired LAN access">Wired LAN access</option>
										<option rel="System Ad" value="VoIP"> VoIP</option>
										<option rel="MIS" value="Data Administration/Data Standardization">Data Administration/Data Standardization</option>
										<option rel="System Ad" value="Web Hosting">Web Hosting</option>
										<option rel="System Ad" value="Live Streaming</">Live Streaming</option>
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
										<td><input type="text" name="rent_equipment" size="60" required/></td>
									</tr>
									<tr>
										<td>Total Amount: </td>
										<td><input type="text" name="rent_total_amount" size="60" required/></td>
									</tr>
									<tr>
										<td>Monthly Payment: </td>
										<td><input type="text" name="rent_monthly_payment" size="60" required/></td>
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
							<div class="all" style="display:none">
							<fieldset id="status">
							Status:
							</fieldset>
							<fieldset id="buttons">
									<input type="radio" name="status"  value="1" checked="true"/> In Process
									<input type="radio" name="status"  value="2" /> Pending
									<input type="radio" name="status"  value="3" /> Done
									<input type="radio" name="status"  value="4" /> Cancelled
			<br/>
							</fieldset>
							
							<br/>
							<br/>
							<fieldset>
							<legend> Reason/s</legend>
							<textarea name="reason" rows="5" cols="100" placeholder="Required"></textarea>
							</fieldset>
							<br/>
							<br/>
							<fieldset id="status">
							Bill Status:
							</fieldset>
							<fieldset id="buttons">
									<input type="radio" name="billstatus"  value="1" checked="true"/> Unbilled
									<input type="radio" name="billstatus"  value="2" /> Billed
			<br/>
							</fieldset>
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
							<br/>
							<fieldset>
							<legend> Comment/s</legend>
							<textarea name="comment2" rows="5" cols="100" placeholder="Recommended"></textarea>
							</fieldset>
							<br/>
							<br/>
							</div>
							
						
						<br/>
						<input type="submit" value="Submit" class="login" name="login" />
						<br/>
				</form>
				<?php
				mysqli_close($con);
				?>
</body>
</html>