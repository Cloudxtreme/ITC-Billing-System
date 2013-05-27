<!-- DISPLAYS JOB REQUEST FORM -->
<?php
	include("sessionGet.php");
	//session_start();
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();

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
		
		$paymentStatus=0;
		$status="In Process";
		$bill_status=0;
		
		$ua_username=$sessionUsername;
		$soa_number = null;
		
		if(isset($_POST['total_amount'])){
			$total_amount=$_POST['total_amount'];
		}
		
		$problem=$_POST['problem'];
		
		$sessionUserSection=$_POST['serviceSection'];
		$service_type="";
		if(isset($_POST['service'])){
			$service_type=$_POST['service'];
		}
		$user->addJobRequest($lastNum, $jrNumber, $sessionUserSection, $date_created, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem, $service_type, $status, $bill_status, $paymentStatus, $date_accomplished, $date_billed, $date_paid, $ua_username, $soa_number, $total_amount);
		if($sessionUserSection!="Rent to Own"){
			$service="";
			if(isset($_POST['service'])){
				$service_type=$_POST['serviceSection'];
			}
			$details=$_POST['details'];
			$total_time=$_POST['total_hours'];
			$assigned_personnel=$_POST['ass_tech'];
			$total_amount=$_POST['total_amount'];
			$released_status="Not Released";
			if($sessionUserSection!="MIS"){
				$r_materials=$_POST['material'];
				$r_comments=$_POST['comment'];
				$user->addGeneral($jrNumber,$service_type,$details,$total_time,$assigned_personnel,$total_amount,$r_materials,$r_comments);
				
				if($sessionUserSection=="Tech Support"){
					$e_brand=$_POST['brand'];
					$e_type=$_POST['type'];
					$e_par=$_POST['parno'];
					$e_accesory=$_POST['accesories'];
					$user->addTech($jrNumber,$e_brand,$e_type,$e_par,$e_accesory,$released_status);
				}
			
				else if($sessionUserSection=="Network Ad"){
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
			$monthly_payment=$_POST['rent_monthly_payment'];
			$end_of_contract=$_POST['rent_end'];
			$user->addRent($jrNumber,$equipment,$total_amount,$monthly_payment,$end_of_contract);
		}
		echo '<script>alert("Added Successfully!")</script>';
		//header('Location: produceJR.php');
	}	
?>

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
			function compute(){
				var amount = document.getElementById('rent_total_amount').value;
				var terms = document.getElementById('rent_terms').value;
				
				if(terms=="12 months")
					document.getElementById('rent_monthly_payment').value = parseInt((amount/12)*1.12);
				else document.getElementById('rent_monthly_payment').value = parseInt((amount/6)*1.12);
			}
			
			function validateForm(){
				var x=document.forms["myForm"]["email"].value;
				var atpos=x.indexOf("@");
				var dotpos=x.lastIndexOf(".");

			}
			function onchange_handler(obj, id,type) {
				var other_id;
				$("select#service").find("option").each(function(){
					if ($(this).attr("rel") == type) {
						$(this).removeAttr("disabled");
						$(this).css("display","block");
					} 
					else{
						$(this).css("display","none");
						$(this).attr("disabled","disabled");
					}
				});
				if(id=="tech"){
					if(obj.checked){
						document.getElementById('tech').style.display = 'block';
						document.getElementById('general').style.display = 'block';
						document.getElementById('mis').style.display = 'block';
						document.getElementById('rent').style.display = 'none';
						document.getElementById('table').style.display = 'none';
					}
				}
				else if(id=="sysad"){
					if(obj.checked){
						document.getElementById('mis').style.display = 'block';
						document.getElementById('general').style.display = 'block';
						document.getElementById('table').style.display = 'none';
						document.getElementById('rent').style.display = 'none';
						document.getElementById('tech').style.display = 'none';
					}
				 }
				 else if(id=="netad"){
					if(obj.checked){
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
				document.getElementById('submitHide').style.display = 'block';
			}
		</script>
	</head>
	
	<body>
		<!-- DISPLAYS FORM -->
		<form name="myForm" method="post" action="produceJR.php" onsubmit="return validateForm();">
			<div class="all">
				<!-- DISPLAYED FOR ALL USERS -->
				<fieldset>
					<legend>CLIENT INFO: </legend>
					<table>
						<tr> 
							<td>Name: </td>
							<td> <input type="text" name="name" size="60" placeholder="Last, Given, Middle" required/> </td>
						</tr>
						<tr>
							<td>Office/Unit: </td>
							<td> 
								<table>
									<tr>
										<td>
											<select name="office" id="office" onchange="hideShowOthers()" style="width:20;">
											<option value="" disabled selected style="display:none;"> Please Choose..</option>
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
											<option value="CFNR - FBS (Temporary)" >CFNR - FBS (Temporary)</option>
											<option value="Personal" <?php if($sessionUserSection=="Rent to Own") echo 'selected';?>>Personal</option>
											<option value="Others" >Others, please specify..</option>
											</select>
										</td>
										<td>
											<input type="text" id="client" name="clientOffice"  placeholder="If Others, please specify..." size="23" style="display:none;"/>
										</td>
									</tr>
								</table>
							</td>
							</tr>
							<tr>
							<td>Designation: </td>
							<td> <input type="text" name="designation" size="60" placeholder="None" required value="<?php if($sessionUserSection=="Rent to Own") echo 'Owner';?>"/> </td>
						</tr>
						<tr> 
							<td>Email: </td>
							<td> <input type="email" name="email" size="60" placeholder="Email Address" required/> </td>
						</tr><tr>
							<td>Tel. No.: </td>
							<td> <input type="text" name="telNumber" size="60" placeholder="Landline/Cellphone" required/> </td>
						</tr>
						
						<tr> 
							<td>Problem: </td>
							<td colspan="3"><textarea name="problem" rows="8" cols="60" placeholder="Please provide if there is any... "></textarea></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				
				<!---<fieldset id="servicesect">
					Service Section:
				</fieldset>-->
				
				<fieldset id="buttons">
					<legend>SERVICE SECTION:</legend>
					<input type="radio" name="serviceSection"  value="Tech Support" id="Tech Support" <?php if($sessionUserSection=="Tech Support"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Tech Support
					<input type="radio" name="serviceSection"  value="System Ad" id="System Ad" <?php if($sessionUserSection=="System Ad"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> System Ad
					<input type="radio" name="serviceSection"  value="Network Ad" id="Network Ad"  <?php if($sessionUserSection=="Network Ad"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Network Ad
					<input type="radio" name="serviceSection"  value="Rent to Own" id="Rent to own"  <?php if($sessionUserSection=="Rent to Own"){ ?> checked="true"<?php } else { ?>disabled <?php } ?>/> Rent to own
					<input type="radio" name="serviceSection"  value="MIS" id="MIS" <?php if($sessionUserSection=="MIS"){ ?> checked="true"<?php } else { ?>disabled <?php } ?> /> MIS
				</fieldset>
				<br/>
			</div>
			
			<div id="tech" <?php if($sessionUserSection=="Tech Support"){ echo" style='display:block'; "; }else{ echo "style='display:none';";}?>>
				<!-- DISPLAYED ONLY WHEN USER'S SECTION IS TECH SUPPORT -->
				<br/>
				<fieldset>
					<legend>EQUIPMENT INFO: </legend>
					<table>
						<tr>
							<td>New/Old: </td>
							<td colspan="3">
								<select name="newOld" id="newOld">
								<option value="New Equipment" selected>New Equipment</option>
								<option value="Old Equipment">Old Equipment</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>Brand/Model: </td>
							<td colspan="3"><input type="text" name="brand" size="78"/> </td>
						</tr>
						<tr>
							<td>Type:</td>
							<td><select name="type">
								<option value="">----------</option>
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
							<td>Par/ARE No.</td>
							<td><input type="text" name="parno" size="60" /> </td>
						</tr>
						<tr>
							<td>Accesories</td>
							<td colspan="3"><textarea name="accesories" rows="8" cols="60"></textarea></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				
			</div>
			
			<div id="mis" <?php if($sessionUserSection!="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- DISPLAYED ONLY WHEN USER'S SECTION IS NOT RENT TO OWN -->
				<fieldset>
					<legend>SERVICE INFO: </legend>
					<table>
						<tr> 
							<td>Service: </td>
							<td>
								<select required name="service" id="service">
									<option value="0" disabled selected style='display:none;'>Please Choose...</option>
									<option rel="Tech Support" <?php if($sessionUserSection!="Tech Support"){ ?> disabled style="display:none;" <?php } ?>value="IT Equipment Repairs and Software Installation"> IT Equipment Repairs and Software Installation</option>
									<option rel="Network Ad" <?php if($sessionUserSection!="Network Ad"){ ?> disabled style="display:none;" <?php } ?> value="Network Installation, Configuration and Testing">Network Installation, Configuration and Testing</option>
									<option rel="Rent to Own"  <?php if($sessionUserSection!="Rent to Own"){ ?> disabled style="display:none;" <?php } ?>value="Rent to Own Computer Package">Rent to Own Computer Package </option>
									<option rel="Network Ad"  <?php if($sessionUserSection!="Network Ad"){ ?> disabled style="display:none;" <?php } ?>value="Wired LAN access">Wired LAN access</option>
									<option rel="System Ad"  <?php if($sessionUserSection!="System Ad"){ ?> disabled style="display:none;" <?php } ?>value="VoIP"> VoIP</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Data Administration/Data Standardization">Data Administration/Data Standardization</option>
									<option rel="System Ad"  <?php if($sessionUserSection!="System Ad"){ ?> disabled style="display:none;" <?php } ?>value="Web Hosting">Web Hosting</option>
									<option rel="System Ad"  <?php if($sessionUserSection!="System Ad"){ ?> disabled style="display:none;" <?php } ?>value="Live Streaming</">Live Streaming</option>
									<option rel="Network Ad"  <?php if($sessionUserSection!="Network Ad"){ ?> disabled style="display:none;" <?php } ?>value="Video Conferencing">Video Conferencing</option>
									<option rel="System Ad"  <?php if($sessionUserSection!="System Ad"){ ?> disabled style="display:none;" <?php } ?>value="E-mail Account Set-up">E-mail Account Set-up</option>
									<option rel="System Ad"  <?php if($sessionUserSection!="System Ad"){ ?> disabled style="display:none;" <?php } ?>value="Mailing List/E-memo">Mailing List/E-memo</option>
									<option rel="Network Ad"  <?php if($sessionUserSection!="Network Ad"){ ?> disabled style="display:none;" <?php } ?>value="Wifi access configuration for UPLB students, faculty and staff">Wifi access configuration for UPLB students, faculty and staff</option>
									<option rel="Tech Support"  <?php if($sessionUserSection!="Tech Support"){ ?> disabled style="display:none;" <?php } ?>value="Maintenance Service">Maintenance Service</option>
									<option rel="Tech Support"  <?php if($sessionUserSection!="Tech Support"){ ?> disabled style="display:none;" <?php } ?>value="Purchase Request Approval for IT equipment, peripherals and supplies">Purchase Request Approval for IT equipment, peripherals and supplies</option>
									<option rel="Tech Support"  <?php if($sessionUserSection!="Tech Support"){ ?> disabled style="display:none;" <?php } ?>value="BAC evaluation for IT equipment, peripherals and supplies">BAC evaluation for IT equipment, peripherals and supplies</option>
									<option rel="Tech Support"  <?php if($sessionUserSection!="Tech Support"){ ?> disabled style="display:none;" <?php } ?>value="Inspection for newly acquired IT equipment, parts, peripherals and supplies">Inspection for newly acquired IT equipment, parts, peripherals and supplies</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="System Analysis and Design">System Analysis and Design</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Software Review and Diagnostic">Software Review and Diagnostic</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Custom Software Development">Custom Software Development</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Database Mangement(Back-end Programming)">Database Mangement(Back-end Programming)</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Software/Database Maintenance">Software/Database Maintenance</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Website Development: Static">Website Development: Static</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Website Development: Dynamic">Website Development: Dynamic</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Custom Web Application Development">Custom Web Application Development</option>
									<option rel="MIS"  <?php if($sessionUserSection!="MIS"){ ?> disabled style="display:none;" <?php } ?>value="Website Maintenance">Website Maintenance</option>
								</select>
							</td>
						</tr>
						<tr> 
							<td>Details: </td>
							<td><textarea name="details" rows="8" cols="60" placeholder="Enter details here... "></textarea></td>
						</tr>
						<tr>
							<td>Assigned Technician: </td>
							<td><input type="text"  value="<?php if($sessionUserSection=="Tech Support")echo "$sessionName";?>" name="ass_tech" size="60" /></td>
						</tr>
						<tr>
							<td>Total number of hours: </td>
							<td><input type="number" name="total_hours" size="60" /></td>
						</tr>
						<tr>
							<td>Total amount: </td>
							<td><input type="number" id="total_amount" name="total_amount" size="60" /></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<br/>
			</div>
			
			<div id="general" <?php if($sessionUserSection=="Tech Support" || $sessionUserSection=="System Ad" || $sessionUserSection=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- DISPLAYED ONLY WHEN USER'S SECTION IS NOT MIS NOR RENT TO OWN -->
				<fieldset>
					<legend>RECOMMENDATIONS </legend>
					<h3>Materials/Equipments</h3>
					<textarea name="material" rows="8" cols="73"></textarea>
					<br/>
					<h3>Comments</h3>
					<textarea name="comment" rows="8" cols="73"></textarea>
				</fieldset>
				<br/>
				<br/>
			</div>
		
			<div id="table" <?php if($sessionUserSection=="Network Ad"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- DISPLAYED ONLY WHEN USER'S SECTION IS NETWORK AD -->
				<br/>
				<table border="1" cellspacing="0">
					<tr>
						<th> EQUIPMENTS PROVIDED/STATIONED</th>
						<th> SERIAL NUMBER/REMARKS </th>
					</tr>
					<tr>
						<td> <input type="text" name="equip1" size="50"/> </td>
						<td> <input type="text" name="serial1" size="45"/> </td>
					</tr>
					<tr>
						<td> <input type="text" name="equip2" size="50"/> </td>
						<td> <input type="text" name="serial2" size="45"/> </td>
					</tr>
					<tr>
						<td> <input type="text" name="equip3" size="50"/> </td>
						<td> <input type="text" name="serial3" size="45"/> </td>
					</tr>
					<tr>
						<td> <input type="text" name="equip4" size="50"/> </td>
						<td> <input type="text" name="serial4" size="45"/> </td>
					</tr>
					<tr>
						<td> <input type="text" name="equip5" size="50"/> </td>
						<td> <input type="text" name="serial5" size="45"/> </td>
					</tr>
				</table>
				<br/>
				<br/>
			</div>
			
			<div id="rent" <?php if($sessionUserSection=="Rent to Own"){ ?> style="display:block" <?php }else{?> style="display:none"<?php }?>>
				<!-- DISPLAYED ONLY WHEN USER'S SECTION IS RENT TO OWN -->
				<br/>
				<br/>
				<fieldset>
					<legend>DETAILS </legend>
					<table>
						<tr>
							<td>Equipment: </td>
							<td><input type="text" name="rent_equipment" id="rent_equipment" size="60" /></td>
						</tr>
						<tr>
							<td>Total Amount: </td>
							<td><input type="number" name="rent_total_amount" id="rent_total_amount" size="60" /></td>
						</tr>
						<tr>
							<td>Terms: </td>
							<td>
								<select name="rent_terms" id="rent_terms">
									<option selected value="12 months">12 months</option>
									<option value="6 months">6 months</option>
								</select>
							
							</td>
						</tr>
						<tr>
							<td>Monthly Payment: </td>
							<td><input type="number" name="rent_monthly_payment" id="rent_monthly_payment" size="60" onmouseover="compute()"/></td>
						</tr>
						<?php
							$stmt = "select DATE_ADD(curdate(), INTERVAL 1 YEAR);";
							$result = mysqli_query($con,$stmt);
							$a = mysqli_fetch_array($result);
							
							$stmt = "select current_date;";
							$result = mysqli_query($con,$stmt);
							$b = mysqli_fetch_array($result);
							
						?>
						<tr>
							<td>Start of Contract: </td>
							<td><input type="date" name="rent_start" id="rent_start" value="<?php echo $b[0]; ?>"></td>
						</tr>
						<tr>
							<td>End of Contract: </td>
							<td><input type="date" name="rent_end" id="rent_end" value="<?php echo $a[0]; ?>"></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<br/>
			</div>
			<br/>
			<div id="submitHide">
				<input type="submit" value="Submit" id="addRequest"name="addRequest" />
				<br/>
			</div>
		</form>
		<?php 
			if($sessionUserType == "Manager"){
				echo'<form method="post" action="job_request_manager.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';	
			}
			else{
				echo'<form method="post" action="job_request_user.php">';
					echo'<input type="submit" value="BACK" name="back" id="back"/>';
				echo'</form>';
			}
		?>
		
	</body>
</html>