<?php
	include("sessionGet.php");
	//session_start();
	//UPDATES THE JOB REQUEST
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	$username = $sessionUsername;
	$user = new functionalityManager;
	
	$jobs = $user->retrieveListOfJR($sessionUserSection);
	
	if(isset($_POST['editRequest'])){
		$date = gmdate("l\, jS \of F Y h:i:s A");
		$jrNumber=$_POST['jrNum'];
		$client_office=$_POST['office'];
		$client_name=$_POST['name'];
		$client_email=$_POST['email'];
		$client_telnum=$_POST['telNumber'];
		$client_designation=$_POST['designation'];
		$new_status=$_POST['status'];
		$old_status=$_SESSION['old_status'];
		$problem=$_POST['problem'];
		$service_type=$_POST['serviceSection'];
		$editComments=$_POST['comment2'];
		$reason=$_POST['reason'];

		$user->updateJobRequest($jrNumber, $new_status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem);
		if($service_type!="Rent to Own"){
			$service_name=$_POST['service'];
			$details=$_POST['details'];
			$total_time=$_POST['total_hours'];
			$assigned_personnel=$_POST['ass_tech'];
			$total_amount=$_POST['total_amount'];

			if($service_type!="MIS"){
				$r_materials=$_POST['material'];
				$r_comments=$_POST['comment'];
				$user->updateGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount,$r_materials,$r_comments);
				
				if($service_type=="Tech Support"){
					$e_newold=$_POST['newOld'];
					$e_brand=$_POST['brand'];
					$e_type=$_POST['type'];
					$e_par=$_POST['parno'];
					$e_accesory=$_POST['accesories'];
					if($new_status=="Done"){ //released status will only be saved if new status is done
						$released_status=$_POST['released_status'];
						$released_by=$_POST['released_by'];
						$released_date=$_POST['released_date'];
					}
					else{ //released status won't be changed if new status is not equal to done
						$released_status="Not Released";
						$released_by="";
						$released_date="";
					}
					$user->updateTech($jrNumber,$e_newold,$e_brand,$e_type,$e_par,$e_accesory,$released_status,$released_by,$released_date);
				}
			
				else if($service_type=="Network Ad"){
					
					$e_provided1=$_POST['equip1'];
					$e_serial1=$_POST['serial1'];
					$e_provided2=$_POST['equip2'];
					$e_serial2=$_POST['serial2'];
					$e_provided3=$_POST['equip3'];
					$e_serial3=$_POST['serial3'];
					$e_provided4=$_POST['equip4'];
					$e_serial4=$_POST['serial4'];
					$e_provided5=$_POST['equip5'];
					$e_serial5=$_POST['equip5'];
					$user->updateNetAd($jrNumber,$e_provided1,$e_serial1,$e_provided2,$e_serial2,$e_provided3,$e_serial3,$e_provided4,$e_serial4,$e_provided5,$e_serial5);
				}
			}
			else{
				$user->updateMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount);
			}
		}
		
		else{
			$equipment=$_POST['rent_equipment'];
			$rent_total_amount=$_POST['rent_total_amount'];
			$terms = $_POST['rent_terms'];
			$monthly_payment=$_POST['rent_monthly_payment'];
			$start_of_contract=$_POST['rent_start'];
			$end_of_contract=$_POST['rent_end'];
			$user->updateRent($jrNumber,$equipment,$rent_total_amount,$terms,$monthly_payment,$start_of_contract,$end_of_contract);
			$user->updateMonthly($jrNumber,$client_name,$monthly_payment);
		}
		if($new_status != $old_status){
			$user->updateStatusLog($jrNumber, $date, $reason, $new_status, $old_status, $username);
		}
		$user->updateEditLog($jrNumber, $editComments, $date, $username);
		if($sessionUserType=="Manager"){	
			header("Location: job_request_manager.php");
		}else if($sessionUserType=="User(Encoder)"){
			header("Location: job_request_user.php");
		}
	}	
?>