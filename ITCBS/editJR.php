<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	//LOADS THE JOB REQUEST INFORMATION
	$user = new functionalityManager;
	$count=$user->countJR($sessionUserSection);
	for($i=0;$i<$count;$i++){
		$index="edit".$i;
		if(isset($_POST[$index])){
			$index2="jrnum".$i;
			$values=$user->getJobRequest($_POST[$index2]);
			$jrNum=$_POST[$index2];
			if($values['SECTION']!="Rent to Own") $gen=$user->getGeneral($_POST[$index2]);
			if($values['SECTION']=="Tech Support") $tech=$user->getTech($_POST[$index2]);
			else if($values['SECTION']=="Rent to Own") $rent=$user->getRent($_POST[$index2]);
			else if($values['SECTION']=="Network Ad") $net=$user->getNetad($_POST[$index2]);
			
			$_SESSION['old_status']=$values['STATUS'];
		}
		
	}
?>
<!-- DISPLAYS INPUT FIELDS WITH THEIR PREVIOUS VALUES AS THE CURRENT VALUES WHICH CAN BE EDITED-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
		<script src="js/jquery-1.9.1.js"></script>
		<script type="text/javascript">
			function hideShowOthers(obj,id){
				var a = document.getElementById('office').value;
				if(a=='Others'){
					$("input#client").css("display","block");
				}else{
					$("input#client").css("display","none");
				}
				
			}
			
			function validateForm(){
				var x=document.forms["myForm"]["email"].value;
				var atpos=x.indexOf("@");
				var dotpos=x.lastIndexOf(".");
				var SECTION=document.forms["myForm"]["serviceSection"];
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
					alert("Invalid Email!");
					return false;
				}
				
				var status=$('input:radio[name=status]:checked').val();
				var prev="<?php echo $values['STATUS']; ?>";
				if(status!=prev){
					var reason=document.forms["myForm"]["reason"].value;
				
					if (reason==null || reason==""){
						alert("Please provide a reason");
						return false;
					}
					return true;
				}
			}
			function requireReason(){
				
			}	
			function onchange_handler(obj, id,type) {
				var other_id;
				$("select#service").find("option").each(function() {
					if ($(this).attr("rel") == type) {
						$(this).removeAttr("disabled");
						$(this).css("display","block");
					}
					else{
						$(this).css("display","none");
						$(this).attr("disabled","disabled");
					}
				});
				var section="<?php echo $sessionUserSection; ?>";
				if(id=="inp" || id=="pen" || id=="can" ){
					if(obj.checked) {
						document.getElementById('release').style.display = 'none';
					}
				 }
				 
				else if(id=="done"){
					if(obj.checked) {
						if(section=="Tech Support"){
							document.getElementById('release').style.display = 'block';
						}
					}
				 }
				
				else if(id=="nr"){
					if(obj.checked) {
						document.getElementById('tab').style.display = 'none';
					}
				 }
				 else if(id=="re"){
					if(obj.checked) {
						document.getElementById('tab').style.display = 'block';
					}
				 }
				else if(id=="tech"){
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
		<form name="myForm" method="post" action="updateJR.php" onsubmit="return validateForm();">
			<div class="all">
				<input type="hidden" name="jrNum" value="<?php echo $jrNum;?>">
				<input type="hidden" name="serviceSection" value="<?php echo $values['SECTION'];?>">
				<input type="hidden" name="origStatus" value="<?php echo $values['STATUS'];?>">
				
				<fieldset>
					<!-- NEEDED TO ALL TYPE OF JOB REQUEST -->
					<legend>CLIENT INFO: </legend>
					<table>
						<tr> 
							<td>Name: </td>
							<td> <input type="text" name="name" size="60" value="<?php echo $values['CLIENT_NAME'];?>"required/> </td>
						</tr>
						<tr>
							<td>Office/Unit: </td>
							<td> 
								<table>
									<tr>
										<td>
											<select name="office" id="office" onchange="hideShowOthers()" style="width:20;">
											<option value="<?php echo $values['CLIENT_OFFICE'];?>"> <?php echo $values['CLIENT_OFFICE'];?></option>
											<option value="OC - Main Office" id="not">OC - Main Office</option>
											<option value="OC - OPR">OC - OPR</option>
											<option value="EXECUTIVE HOUSE">EXECUTIVE HOUSE</option>
											<option value="GUEST HOUSE">GUEST HOUSE</option>
											<option value="OC - ICO">OC - ICO</option>
											<option value="OC - LEGAL OFFICE">OC - LEGAL OFFICE</option>
											<option value="OC - OAR">OC - OAR</option>
											<option value="OVCI - OSA-DO">OVCI - OSA-DO</option>
											<option value="SWSU">SWSU</option>
											<option value="OVCI - OSA-CTD">OVCI - OSA-CTD</option>
											<option value="OVCI - OSA-ISS">OVCI - OSA-ISS</option>
											<option value="OVCI - OSA-LRP">OVCI - OSA-LRP</option>
											<option value="OVCI - OSA-SFAD">OVCI - OSA-SFAD</option>
											<option value="OVCI - OSA-SOAD/TERC">OVCI - OSA-SOAD/TERC</option>
											<option value="STUD HOUSING">STUD HOUSING</option>
											<option value="OVCA - Main Office">OVCA - Main Office</option>
											<option value="OVCA - Accounting Office">OVCA - Accounting Office</option>
											<option value="OVCCA - BAO">OVCCA - BAO</option>
											<option value="OVCCA - CEC">OVCCA - CEC</option>
											<option value="OC - BMO">OC - BMO</option>
											<option value="OVCA - Cashiers Office">OVCA - Cashiers Office</option>
											<option value="OVCA - HRDO">OVCA - HRDO</option>
											<option value="OVCA - RMO">OVCA - RMO</option>
											<option value="OVCA - SPMO">OVCA - SPMO</option>
											<option value="OVCA - SPMO (BAC SEC)">OVCA - SPMO (BAC SEC)</option>
											<option value="OVCCA - Main Office">OVCCA - Main Office</option>
											<option value="OVCCA - LGMO">OVCCA - LGMO</option>
											<option value="OVCCA - UHS">OVCCA - UHS</option>
											<option value="OVCCA - UHO">OVCCA - UHO</option>
											<option value="OVCCA - UPF">OVCCA - UPF</option>
											<option value="OVCI - Main Office">OVCI - Main Office</option>
											<option value="OVCI - ILC">OVCI - ILC</option>
											<option value="OVCI - DMST">OVCI - DMST</option>
											<option value="OC - OIL">OC - OIL</option>
											<option value="OVCI - OUR">OVCI - OUR</option>
											<option value="OVCI - SWF">OVCI - SWF</option>
											<option value="OVCI - University Library">OVCI - University Library</option>
											<option value="OVCPD - Main Office">OVCPD - Main Office</option>
											<option value="OVCPD - CPDO">OVCPD - CPDO</option>
											<option value="OVCPD - ITC">OVCPD - ITC</option>
											<option value="OVCPD - PPMSO">OVCPD - PPMSO</option>
											<option value="OVCRE - Main Office">OVCRE - Main Office</option>
											<option value="OVCRE - BIOTECH">OVCRE - BIOTECH</option>
											<option value="OVCRE - MNH">OVCRE - MNH</option>
											<option value="OVCRE - OICA">OVCRE - OICA</option>
											<option value="OVCRE - Ugnayan ng Pahinungod">OVCRE - Ugnayan ng Pahinungod</option>
											<option value="OVCRE - UPLB GENDER CENTER">OVCRE - UPLB GENDER CENTER</option>
											<option value="CA - DO">CA - DO</option>
											<option value="CA - SO">CA - SO</option>
											<option value="CA - CES">CA - CES</option>
											<option value="CA - ASC">CA - ASC</option>
											<option value="CA - ADSC">CA - ADSC</option>
											<option value="CA - CPC">CA - CPC</option>
											<option value="CA - CSC">CA - CSC</option>
											<option value="CA - FSC">CA - FSC</option>
											<option value="CAS - DO">CAS - DO</option>
											<option value="CAS - SO">CAS - SO</option>
											<option value="CAS - DHK">CAS - DHK</option>
											<option value="CAS - DHUM">CAS - DHUM</option>
											<option value="CAS - DSS">CAS - DSS</option>
											<option value="CAS - IBS">CAS - IBS</option>
											<option value="CAS - IC">CAS - IC</option>
											<option value="CAS - ICS">CAS - ICS</option>
											<option value="CAS - IMSP">CAS - IMSP</option>
											<option value="CAS - INSTAT">CAS - INSTAT</option>
											<option value="CAS - UPRHS">CAS - UPRHS</option>
											<option value="CDC - DO">CDC - DO</option>
											<option value="CDC - SO">CDC - SO</option>
											<option value="CDC - DBT">CDC - DBT</option>
											<option value="CDC - DJ">CDC - DJ</option>
											<option value="CDC - EC">CDC - EC</option>
											<option value="CDC - SC">CDC - SC</option>
											<option value="CEAT - DO">CEAT - DO</option>
											<option value="CEAT - SO">CEAT - SO</option>
											<option value="CEAT - AMTEC">CEAT - AMTEC</option>
											<option value="CEAT - CHEM ENG">CEAT - CHEM ENG</option>
											<option value="CEAT - CE">CEAT - CE</option>
											<option value="CEAT - EE">CEAT - EE</option>
											<option value="CEAT - ES">CEAT - ES</option>
											<option value="CEAT - IE">CEAT - IE</option>
											<option value="CEAT - IAE-DO">CEAT - IAE-DO</option>
											<option value="CEM - DO">CEM - DO</option>
											<option value="CEM - SO">CEM - SO</option>
											<option value="CEM - DAM">CEM - DAM</option>
											<option value="CEM - DAE">CEM - DAE</option>
											<option value="CEM - DE">CEM - DE</option>
											<option value="CFNR - DO">CFNR - DO</option>
											<option value="CFNR - SO">CFNR - SO</option>
											<option value="CFNR - FBS">CFNR - FBS</option>
											<option value="CFNR - FPPS">CFNR - FPPS</option>
											<option value="CFNR - SFFG">CFNR - SFFG</option>
											<option value="CFNR - FDC">CFNR - FDC</option>
											<option value="CFNR - IAF">CFNR - IAF</option>
											<option value="CFNR - IRNR">CFNR - IRNR</option>
											<option value="CFNR - MCME">CFNR - MCME</option>
											<option value="CFNR - TREES">CFNR - TREES</option>
											<option value="CHE - DO">CHE - DO</option>
											<option value="CHE - SO">CHE - SO</option>
											<option value="CHE - BIDANI">CHE - BIDANI</option>
											<option value="CHE - DCERP">CHE - DCERP</option>
											<option value="CHE - HFDS">CHE - HFDS</option>
											<option value="CHE - IHNF">CHE - IHNF</option>
											<option value="CHE - DSDS">CHE - DSDS</option>
											<option value="CPAf - DO">CPAf - DO</option>
											<option value="CEM - ICOPED (ACCI)">CEM - ICOPED (ACCI)</option>
											<option value="CPAf - CISC">CPAf - CISC</option>
											<option value="CPAf - ICE">CPAf - ICE</option>
											<option value="CPAf - IGRD">CPAf - IGRD</option>
											<option value="CPAf - CSPPS">CPAf - CSPPS</option>
											<option value="CVM - DO">CVM - DO</option>
											<option value="CVM - SO">CVM - SO</option>
											<option value="CVM LIBRARY">CVM LIBRARY</option>
											<option value="CVM - DBVS">CVM - DBVS</option>
											<option value="CVM - DVCS">CVM - DVCS</option>
											<option value="CVM - DVPS">CVM - DVPS</option>
											<option value="CVM - VTH">CVM - VTH</option>
											<option value="GRADUATE SCHOOL">GRADUATE SCHOOL</option>
											<option value="SESAM">SESAM</option>
											<option value="CDC">CDC</option>
											<option value="OVCI - OSA-SDT">OVCI - OSA-SDT</option>
											<option value="CFNR - OCREL">CFNR - OCREL</option>
											<option value="CFNR - AS">CFNR - AS</option>
											<option value="OC - PCC">OC - PCC</option>
											<option value="UP PROVIDENT FUND">UP PROVIDENT FUND</option>
											<option value="OVCRE - CHED-ZRC">OVCRE - CHED-ZRC</option>
											<option value="CEAT - AMDP">CEAT - AMDP</option>
											<option value="CEAT LIBRARY">CEAT LIBRARY</option>
											<option value="CPAf - GPO">CPAf - GPO</option>
											<option value="COA">COA</option>
											<option value="OVCRE - CTTE">OVCRE - CTTE</option>
											<option value="CA - PAS">CA - PAS</option>
											<option value="CFNR LIBRARY">CFNR LIBRARY</option>
											<option value="CFNR - ADO">CFNR - ADO</option>
											<option value="CA PUBLICATION">CA PUBLICATION</option>
											<option value="OVCRE - ANNEX">OVCRE - ANNEX</option>
											<option value="OVCI - UPO">OVCI - UPO</option>
											<option value="AUPWU">AUPWU</option>
											<option value="OVCPD - TITLING OFFICE">OVCPD - TITLING OFFICE</option>
											<option value="OVCI - OSA-USC">OVCI - OSA-USC</option>
											<option value="OVCI - OSA - PERSPECTIVE">OVCI - OSA - PERSPECTIVE</option>
											<option value="CPAf - KMO">CPAf - KMO</option>
											<option value="CEM LIBRARY">CEM LIBRARY</option>
											<option value="CAS - IBS-LIMNO STATION">CAS - IBS-LIMNO STATION</option>
											<option value="Czarlinas Department">Czarlinas Department</option>
											<option value="OVCI - MCOILC">OVCI - MCOILC</option>
											<option value="UP System - OP">UP System - OP</option>
											<option value="UP System - OVPAA">UP System - OVPAA</option>
											<option value="UP System - OVPF">UP System - OVPF</option>
											<option value="UP System - OVPA">UP System - OVPA</option>
											<option value="UP System - OVPD">UP System - OVPD</option>
											<option value="UP System - OVPPA">UP System - OVPPA</option>
											<option value="UP System - OSU">UP System - OSU</option>
											<option value="UPLB-FI">UPLB-FI</option>
											<option value="CEAT - IAE-AMD">CEAT - IAE-AMD</option>
											<option value="CEAT - IAE-ABPROD">CEAT - IAE-ABPROD</option>
											<option value="CEAT - IAE-LWRD">CEAT - IAE-LWRD</option>
											<option value="CEAT - IAE-AFSD">CEAT - IAE-AFSD</option>
											<option value="CFNR - FBS (Temporary)">CFNR - FBS (Temporary)</option>
											<option value="Personal">Personal</option>
											<option value="Others" >Others, please specify..</option>
											</select>
										</td>
										<td>
											<input type="text" id="client" name="clientOffice"  placeholder="If Others, please specify..." size="23" style="display:none;"/>
										</td>
									</tr>
								</table>
							</td>
							</tr><tr>
							<td>Designation: </td>
							<td> <input type="text" name="designation" size="55" value="<?php echo $values['CLIENT_DESIGNATION'];?>"/> </td>
						</tr>
						<tr> 
							<td>Email: </td>
							<td> <input type="email" name="email" size="60" value="<?php echo $values['CLIENT_EMAIL'];?>"required/> </td>
							</tr><tr>
							<td>Tel. No.: </td>
							<td> <input type="text" name="telNumber" size="55" value="<?php echo $values['CLIENT_TELNUM'];?>"required/> </td>
						</tr>
						<tr> 
							<td>Problem: </td>
							<td colspan="3"><textarea name="problem" rows="8" cols="60" placeholder="<?php if($values['PROBLEM']=="") echo "Please provide if there is any... "; else echo "";?>"><?php echo $values['PROBLEM'];?></textarea></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<br/>
			</div>
			<div id="tech" <?php if($values['SECTION']=="Tech Support"){ echo" style='display:block'; "; }else{ echo "style='display:none';";}?> >
				<!-- WILL ONLY BE DISPLAYED WHEN THE USER'S SECTION IS TECH SUPPORT -->
				<fieldset>
					<legend>EQUIPMENT INFO: </legend>
					<table>
						<tr>
							<td>New/Old: </td>
							<td colspan="3">
								<select name="newOld" id="newOld">
								<option value=" <?php echo $tech['NEWOLD'];?> "> <?php echo $tech['NEWOLD'];?> </option>
								<option value="New Equipment">New Equipment</option>
								<option value="Old Equipment">Old Equipment</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Brand/Model: </td>
							<td colspan="3"><input type="text" name="brand" size="78" value= "<?php echo $tech['E_BRAND']; ?>"/> </td>
						</tr>
						<tr>
							<td>Type:</td>
							<td><select name="type">
								<option value=" <?php echo $tech['E_TYPE'];?> "> <?php echo $tech['E_TYPE'];?> </option>
								<option value="COMPUTER(Desktop)">COMPUTER(Desktop)</option>
								<option value="COMPUTER(Laptop)">COMPUTER(Laptop)</option>
								<option value="MONITOR(CRT)">MONITOR(CRT)</option>
								<option value="MONITOR(LCD)">MONITOR(LCD)</option>
								<option value="PRINTER(Inkjet)">PRINTER(Inkjet)</option>
								<option value="PRINTER(Laserjet)">PRINTER(Laserjet)</option>
								<option value="PRINTER(Dot Matrix)">PRINTER(Dot Matrix)</option>
								<option value="EXTERNAL DRIVE">EXTERNAL DRIVE</option>
								<option value="PROJECTOR">PROJECTOR</option>
								<option value="AC/DC ADAPTOR">AC/DC ADAPTOR</option>
								<option value="SCANNER">SCANNER</option>
								<option value="VOIP PHONE">VOIP PHONE</option>
								<option value="SERVER">SERVER</option>
								<option value="UPS">UPS</option>
								<option value="SWITCH">SWITCH</option>
								<option value="WIRELESS ROUTER">WIRELESS ROUTER</option>
								<option value="TABLET">TABLET</option>
								<option value="DVD PORTABLE">DVD PORTABLE</option>
								<option value="POWER ADAPTOR">POWER ADAPTOR</option>
								<option value="IPAD">IPAD</option>
								<option value="FLASH DRIVE">FLASH DRIVE</option>
								<option value="Massager">Massager</option>
								<option value="Electric Oven">Electric Oven</option>
								<option value="Software">Software</option>
								<option value="Mouse">Mouse</option>
								<option value="Electric Fan">Electric Fan</option>
								<option value="LED TV">LED TV</option>
								</select>
							</td>
							</tr><tr>
							<td>Par No.</td>
							<td><input type="text" name="parno" size="60" value="<?php if($values['SECTION']=="Tech Support") echo $tech['E_PAR']; else echo "";?>"/> </td>
						</tr>
						<tr>
							<td>Accesories</td>
							<td colspan="3"><textarea name="accesories" rows="8" cols="60"><?php if($values['SECTION']=="Tech Support") echo $tech['E_ACCESORY']; else echo "";?></textarea></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<br/>
			</div>
			<div id="mis" <?php if($values['SECTION']!="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- WILL BE DISPLAYED TO ALL SECTIONS EXCEPT RENT TO OWN-->
				<fieldset>
					<legend>SERVICE INFO: </legend>
					<table>
						<tr> 
							<td>Service: </td>
							<!-- WILL ONLY DISPLAY SERVICES AVAILABLE ON THE SELECTED SECTION-->
							<td>
								<select name="service" id="service">
									<option rel="Tech Support" value="IT Equipment Repairs and Software Installation" <?php if($values['SECTION']!="Tech Support"){?> disabled style='display:none;'<?php } if($values['SERVICE_TYPE']=="IT Equipment Repairs and Software Installation"){?> selected <?php } ?>>IT Equipment Repairs and Software Installation</option>
									<option rel="Network Ad" value="Network Installation, Configuration and Testing" <?php if($values['SECTION']!="Network Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Network Installation, Configuration and Testing"){?> selected <?php } ?>>Network Installation, Configuration and Testing</option>
									<option rel="Network Ad" value="Wired LAN access" <?php if($values['SECTION']!="Network Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Wired LAN access"){?>selected <?php } ?>>Wired LAN access</option>
									<option rel="System Ad" value="VoIP" <?php if($values['SECTION']!="System Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="VoIP"){?>selected <?php } ?>> VoIP</option>
									<option rel="MIS" value="Data Administration/Data Standardization" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Data Administration/Data Standardization"){?>selected <?php } ?>>Data Administration/Data Standardization</option>
									<option rel="System Ad" value="Web Hosting" <?php if($values['SECTION']!="System Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Web Hosting"){?>selected <?php } ?>>Web Hosting</option>
									<option rel="System Ad" value="Live Streaming" <?php if($values['SECTION']!="System Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Live Streaming"){?>selected <?php } ?>>Live Streaming</option>
									<option rel="Network Ad" value="Video Conferencing" <?php if($values['SECTION']!="Network Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Video Conferencing"){?>selected <?php } ?>>Video Conferencing</option>
									<option rel="System Ad" value="E-mail Account Set-up" <?php if($values['SECTION']!="System Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="E-mail Account Set-up"){?>selected <?php } ?>>E-mail Account Set-up</option>
									<option rel="System Ad" value="Mailing List/E-memo" <?php if($values['SECTION']!="System Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Mailing List/E-memo"){?>selected <?php } ?>>Mailing List/E-memo</option>
									<option rel="Network Ad" value="Wifi access configuration for UPLB students, faculty and staff" <?php if($values['SECTION']!="Network Ad"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Wifi access configuration for UPLB students, faculty and staff"){?>selected <?php } ?>>Wifi access configuration for UPLB students, faculty and staff</option>
									<option rel="Tech Support" value="Maintenance Service" <?php if($values['SECTION']!="Tech Support"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Maintenance Service"){?>selected <?php } ?>>Maintenance Service</option>
									<option rel="Tech Support" value="Purchase Request Approval for IT equipment, peripherals and supplies" <?php if($values['SECTION']!="Tech Support"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Purchase Request Approval for IT equipment, peripherals and supplies"){?>selected <?php } ?>>Purchase Request Approval for IT equipment, peripherals and supplies</option>
									<option rel="Tech Support" value="BAC evaluation for IT equipment, peripherals and supplies" <?php if($values['SECTION']!="Tech Support"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="BAC evaluation for IT equipment, peripherals and supplies"){?>selected <?php } ?>>BAC evaluation for IT equipment, peripherals and supplies</option>
									<option rel="Tech Support" value="Inspection for newly acquired IT equipment, parts, peripherals and supplies" <?php if($values['SECTION']!="Tech Support"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Inspection for newly acquired IT equipment, parts, peripherals and supplies"){?>selected <?php } ?>>Inspection for newly acquired IT equipment, parts, peripherals and supplies</option>
									<option rel="MIS" value="System Analysis and Design" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="System Analysis and Design"){?>selected <?php } ?>>System Analysis and Design</option>
									<option rel="MIS" value="Software Review and Diagnostic" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Software Review and Diagnostic"){?>selected <?php } ?>>Software Review and Diagnostic</option>
									<option rel="MIS" value="Custom Software Development" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Custom Software Development"){?>selected <?php } ?>>Custom Software Development</option>
									<option rel="MIS" value="Database Mangement(Back-end Programming)" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Database Mangement(Back-end Programming)"){?>selected <?php } ?>>Database Mangement(Back-end Programming)</option>
									<option rel="MIS" value="Software/Database Maintenance" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Software/Database Maintenance"){?>selected <?php } ?>>Software/Database Maintenance</option>
									<option rel="MIS" value="Website Development: Static" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Website Development: Static"){?>selected <?php } ?>>Website Development: Static</option>
									<option rel="MIS" value="Website Development: Dynamic" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Website Development: Dynamic"){?>selected <?php } ?>>Website Development: Dynamic</option>
									<option rel="MIS" value="Custom Web Application Development" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Custom Web Application Development"){?>selected <?php } ?>>Custom Web Application Development</option>
									<option rel="MIS" value="Website Maintenance" <?php if($values['SECTION']!="MIS"){?> disabled style='display:none;' <?php } if($values['SERVICE_TYPE']=="Website Maintenance"){?>selected <?php } ?>>Website Maintenance</option>
								</select>
							</td>
						</tr>
						<tr> 
							<td>Details: </td>
							<td><textarea name="details" rows="8" cols="60" placeholder="<?php if($gen['DETAILS']=="") echo "Enter details here... "; else echo "";?>"><?php echo $gen['DETAILS'];?></textarea></td>
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
			<div id="general" <?php if($values['SECTION']=="Tech Support" || $values['SECTION']=="System Ad" || $values['SECTION']=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
			 <!-- WILL BE DISPLAYED IF THE USER'S SECTION IS NOT MIS NOR RENT TO OWN-->
				<fieldset>
					<legend>RECOMMENDATIONS </legend>
					<h3>Materials/Equipments</h3>
					<textarea name="material" rows="8" cols="73"><?php echo $gen['R_MATERIALS'];?></textarea>
					<br/>
					<h3>Comments</h3>
					<textarea name="comment" rows="8" cols="73"><?php echo $gen['R_COMMENTS'];?></textarea>
				</fieldset>
				<br/>
				<br/>
			</div>
			<div id="table" <?php if($values['SECTION']=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- WILL ONLY DISPLAYED IF USER'S SECTION IS NETWORK AD-->
				<br/>
				<table border="1" cellspacing="0" >
					<tr>
						<th> EQUIPMENTS PROVIDED/STATIONED</th>
						<th> SERIAL NUMBER/REMARKS </th>
					</tr>
					<tr>
						<td>
							<input type='text' name='equip1' size='50' value="<?php echo $net['EQUIP1']; ?>"/>
						</td>
						<td>
							<input type='text' name='serial1' size='45' value="<?php echo $net['SERIAL1']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type='text' name='equip2' size='50' value="<?php echo $net['EQUIP2']; ?>"/>
						</td>
						<td>
							<input type='text' name='serial2' size='45' value="<?php echo $net['SERIAL2']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type='text' name='equip3' size='50' value="<?php echo $net['EQUIP3']; ?>"/>
						</td>
						<td>
							<input type='text' name='serial3' size='45' value="<?php echo $net['EQUIP3']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type='text' name='equip4' size='50' value="<?php echo $net['EQUIP4']; ?>"/>
						</td>
						<td>
							<input type='text' name='serial4' size='45' value="<?php echo $net['SERIAL4']; ?>"/>
						</td>
					</tr>
					<tr>
						<td>
							<input type='text' name='equip5' size='50' value="<?php echo $net['EQUIP5']; ?>"/>
						</td>
						<td>
							<input type='text' name='serial5' size='45' value="<?php echo $net['SERIAL5']; ?>"/>
						</td>
					</tr>
				</table>
				<br/>
				<br/>
			</div>	
			<div id="rent" <?php if($values['SECTION']=="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- WILL ONLY BE DISPLAYED IF USER'S SECTION IS RENT TO OWN-->
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
							<td>Terms: </td>
							<td>
								<select name="rent_terms" id="rent_terms">
									<option selected value="<?php echo $rent['TERMS'];?>"><?php echo $rent['TERMS'];?></option>
									<option value="12 months">12 months</option>
									<option value="6 months">6 months</option>
								</select>
							
							</td>
						</tr>
						<tr>
							<td>Monthly Payment: </td>
							<td><input type="text" name="rent_monthly_payment" size="60" value="<?php echo $rent['MONTHLY_PAYMENT'];?>"/></td>
						</tr>
						<tr>
							<td>Start of Contract: </td>
							<td><input type="date" name="rent_start" id="rent_start" value="<?php echo $rent['START_OF_CONTRACT'];?>"></td>
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
				<!-- DISPLAYS THE JOB REQUEST'S CURRENT STATUS -->
				<fieldset id="status">
					<legend>Status:</legend>
					<fieldset id="buttons">
						<input type="radio" name="status"  value="In Process" checked="true" <?php if($values['STATUS']=="In Process"){ ?> checked="true"<?php }?> onchange="onchange_handler(this,'inp',this.value)"/> In Process
						<input type="radio" name="status"  value="Pending" <?php if($values['STATUS']=="Pending"){ ?> checked="true"<?php }?>onchange="onchange_handler(this,'pen',this.value)"/> Pending
						<input type="radio" name="status"  value="Done" <?php if($values['STATUS']=="Done"){ ?> checked="true"<?php }?>onchange="onchange_handler(this,'done',this.value)"/> Done
						<input type="radio" name="status"  value="Cancelled" onchange="onchange_handler(this,'can',this.value)"<?php if($values['STATUS']=="Cancelled"){ ?> checked="true"<?php }?>/> Cancelled
						<br/>
					</fieldset>
					
					<?php
						$jr = $values['JR_NUMBER'];
						$stmt = "SELECT soa_number, bill_status, payment_status,date_paid FROM job_request
							where jr_number ='$jr';";
				
						$result = mysqli_query($con,$stmt);
						
						$row=mysqli_fetch_assoc($result);
						
						$soa=$row['soa_number'];
						
						
						echo '<br/><br/><fieldset>';
							echo '<table>';
								echo '<tr>';
									echo '<td class="highlight">';
										echo "Bill";
									echo '</td>';
									echo '<td>';
										echo '<input type="text" readonly value="'.$row['bill_status'].'"/>';
									echo '</td>';
								echo '</tr>';
							echo '</table>';
							if($row['bill_status']=="Billed"){
								//WILL ONLY BE DISPLAYED IF JOB REQUEST HAS BEEN BILLED
								echo '<table>';
									echo '<tr>';
										echo '<td class="highlight">';
											echo "Bill Number";
										echo '</td>';
										echo '<td>';
											$stmt2 = "SELECT soa_main_number from soa where soa_number=$soa;";
											$result2 = mysqli_query($con,$stmt2);
											$a=mysqli_fetch_array($result2);
						
											echo '<input type="text" readonly value="'.$a[0].'"/>';
										echo '</td>';
									echo '</tr>';
								echo '</table>';
							}
							echo '<table>';
								echo '<tr>';
									echo '<td class="highlight">';
										echo "Payment";
									echo '</td>';
									echo '<td>';
										echo '<input type="text" readonly value="'.$row['payment_status'].'"/>';
									echo '</td>';
								echo '</tr>';
							echo '</table>';
							if($row['payment_status']=="Paid"){
								//WILL BE DISPLAYED ONLY IF JOB REQUEST HAS BEEN PAID
								echo '<table>';
									echo '<tr>';
										echo '<td class="highlight">';
											echo "Date Paid";
										echo '</td>';
										echo '<td>';
											echo '<input type="text" readonly value="'.$row['date_paid'].'"/>';
										echo '</td>';
									echo '</tr>';
								echo '</table>';
							}
						echo '</fieldset>';
					?>
				
					<br/>
					
					<!-- will displayed (can be released) if and only if hob request's status is done. for tech support only-->
					<fieldset id="release" <?php if($values['STATUS']=="Done" && $values['SECTION']=="Tech Support"){ ?> style="display:block" <?php }else{ ?> style="display:none" <?php } ?>>
						<legend>Released</legend>
						<input type="radio" name="released_status"  value="Not Released" checked="true" <?php if($tech['RELEASED_STATUS']=="Not Released"){ ?> checked="true"<?php }?> onchange="onchange_handler(this, 'nr',this.value)"/> Not Released
						<input type="radio" name="released_status"  value="Released" onchange="onchange_handler(this, 're',this.value)"<?php if($tech['RELEASED_STATUS']=="Released"){ ?> checked="true"<?php }?> /> Released
						<table id="tab" <?php if($tech['RELEASED_STATUS']=="Released"){ ?> style="display:block" <?php } else{ ?> style="display:none" <?php } ?>>
						<tr>
						<td>Released By: </td>
						<td><input type="text" name="released_by" value="<?php echo $tech['RELEASED_BY']; ?>" /></td>
						</tr>
						<tr>
						<td>Released Date: </td>
						<td><input type="date" name="released_date" value="<?php echo $tech['RELEASED_DATE']; ?>"/></td>
						</tr>
						</table>
					</fieldset>
					<br/>
					
					<fieldset>
						<!-- REQUIRED WHEN STATUS HAS BEEN EDITED-->
						<legend> Edit Reason/s</legend>
						<textarea id="reason" name="reason" rows="8" cols="70" placeholder="Required"></textarea>
					</fieldset>
					<br/><br/>
					<?php
					//DISPLAYS STATUS LOGS OF THE JOB REQUEST
					$jr = $values['JR_NUMBER'];
					$stmt = "SELECT * FROM status_log where jr_number ='$jr';";
					
					$result = mysqli_query($con,$stmt);
					
					echo '<fieldset>';
					echo '<legend>Log</legend>';
						echo '<table class="editJR" border="1px solid" width="100%" cellspacing="0">';
						echo '<col width="30%"><col width="15%"><col width="40%"><col width="15%">';
							echo '<tr>';
								echo '<th>';
									echo "Date ";
								echo '</th>';
								echo '<th>';
									echo "Status";
								echo '</th>';
								echo '<th>';
									echo "Reason";
								echo '</th>';
								echo '<th>';
									echo "Username";
								echo '</th>';
							echo '</tr>';
							
							while($row=mysqli_fetch_assoc($result)){
								echo '<tr>';
									echo '<td>';
										echo $row['DATE_TIME'];
									echo '</td>';
								
									echo '<td>';
										echo $row['NEW_STATUS'];
									echo '</td>';
								
									echo '<td>';
										echo $row['REASON'];
									echo '</td>';
								
									echo '<td>';
										echo $row['UA_USERNAME'];
									echo '</td>';
								echo '</tr>';
							}
						echo '</table>';
					echo '</fieldset>';
					?>
				</fieldset>
				<br/>
				<br/>
				<fieldset>
					<legend> Edit Comment/s</legend>
					<textarea name="comment2" rows="8" cols="78" placeholder="Recommended"></textarea>
					<br/><br/>
					<?php
					$jr = $values['JR_NUMBER'];
					$stmt = "SELECT * FROM edit_log where jr_number ='$jr';";
					
					$result = mysqli_query($con,$stmt);
					//DISPLAYS EDIT LOGS OF THE JOB REQUEST
					echo '<fieldset>';
					echo '<legend>Log</legend>';
						echo '<table class="editJR" border="1px solid" width="100%" cellspacing="0">';
						echo '<col width="30%"><col width="55%"><col width="15%">';
							echo '<tr>';
								echo '<th>';
									echo "Date ";
								echo '</th>';
								echo '<th>';
									echo "Comments";
								echo '</th>';
								echo '<th>';
									echo "Username";
								echo '</th>';
							echo '</tr>';
							
							while($row=mysqli_fetch_assoc($result)){
								echo '<tr>';
									echo '<td>';
										echo $row['DATE_TIME'];
									echo '</td>';
								
									echo '<td>';
										echo $row['COMMENTS'];
									echo '</td>';
								
									echo '<td>';
										echo $row['UA_USERNAME'];
									echo '</td>';
								echo '</tr>';
							}
						echo '</table>';
					echo '</fieldset>';
				?>
				</fieldset>
				<br/>
			</div>
			<input type="submit" value="Submit" id="editRequest" name="editRequest"/>
		</form>
		<?php 
			if($sessionUserType=="Manager"){
				echo'<form method="post" action="job_request_manager.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';
			}
			else if($sessionUserType=="User(Encoder)"){
				echo'<form method="post" action="job_request_user.php">';
					echo'<input type="submit" value="BACK" id="back" name="back"/>';
				echo'</form>';
			}
		?>
	</body>
</html>