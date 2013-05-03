<?php
	include("functions.php");
	$admin = new databaseManager;
	
	$jobs = $admin->retrieveJobRequests();
	
	if(isset($_POST['editRequest'])){
	//jOB REQUEST
	echo '<script>alert("'.$_POST['origStatus'].$_POST['status'].'")</script>';
	$jrNumber=$_POST['jrNum'];
	$client_office=$_POST['office'];
	$client_name=$_POST['name'];
	$client_email=$_POST['email'];
	$client_telnum=$_POST['telNumber'];
	$client_designation=$_POST['designation'];
	$status=$_POST['status'];
	$problem=$_POST['problem'];
	//if($_POST['problem']=="") $problem="null";
	//else $problem=$_POST['problem'];
	$service_type=$_POST['serviceSection'];
	$date_time=date('timestamp');
	$reason=$_POST['reason'];
	$comment2=$_POST['comment2'];
	if($service_type!="Rent to Own") $total_amount=$_POST['total_amount'];
	else $total_amount=$_POST['rent_total_amount'];
	$admin->updateJobRequest($jrNumber, $status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem,$total_amount);
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
			$admin->updateGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$r_materials,$r_comments);
			
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
				$admin->updateTech($jrNumber,$e_brand,$e_type,$e_par,$e_accesory);
			}
		
			else if($service_type=="Network Ad"){
				//net ad -table
				$i=1; //count for the table
				$admin->removeNetad($jrNumber);
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
			$admin->updateMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel);
		}
	}
	
	else{
	//rent
	$equipment=$_POST['rent_equipment'];
	$monthly_payment=$_POST['rent_monthly_payment'];
	$end_of_contract=$_POST['rent_end'];
	$admin->updateRent($jrNumber,$equipment,$monthly_payment,$end_of_contract);
	}
	
	$admin->addEditLog($jrNumber,$comment2);
	
	if($_POST['origStatus']!=$_POST['status']){
		$admin->addStatusLog($jrNumber,$reason,$status);
	}
}	
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<table border="1px solid black">
		<tr>
			<td>JR_NUMBER</td>
			<td></td>
		<tr>
		<?php
			for($i=0 ; $i<count($jobs) ; $i++){
				echo '<tr>';
					echo '<td>';
						echo $jobs[$i]->getjrNumber();
					echo '</td>';
					echo '<td>';
						echo '<form method="post" action="editJR.php"> <input type="hidden" name="jrnum'.$i.'" value="'.$jobs[$i]->getjrNumber().'"><input type="submit" value="VIEW" name="submit'.$i.'"/></form>';
					echo '</td>';
				echo '</tr>';
			}
		?>
	</table>
</body>
</html>