<?php
include("classes.php");

//connect to the database
$con = mysqli_connect("localhost","root","","itcbs_db");

class databaseManager
{	
	public function addAccount($username,$password,$name,$level,$section){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO user_account VALUES(
		'$username','$name','$level','$section','$password'
		);";
		$a=mysqli_query($con,$stmt);
		
		if($a)
			return 1;
		else return 0;
	}
	
	public function addJobRequest($jrNumber, $paymentStatus, $status, $date_created, $bill_status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $username, $problem, $service_type,$total_amount){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO job_request VALUES('$jrNumber', '$date_created','$client_office', '$client_name', '$client_email', '$client_telnum', '$client_designation', '$problem', '$service_type', '$status', '$bill_status','$paymentStatus', NULL, NULL, NULL, '$username', NULL,'$total_amount');";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function updateJobRequest($jrNumber, $status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem,$total_amount){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "UPDATE job_request SET  status='$status', client_office='$client_office', client_name='$client_name', client_email='$client_email', client_telnum='$client_telnum', client_designation='$client_designation', problem='$problem', total_amount='$total_amount' WHERE jr_number='$jrNumber';";
		//$stmt="UPDATE job_request SET problem='asdasd' WHERE JR_NUMBER='$jrNumber';";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function addGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$r_materials,$r_comments){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO general_jr VALUES('$jrNumber','$service_name','$details','$total_time','$assigned_personnel','$r_materials','$r_comments');";
		
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function updateGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$r_materials,$r_comments){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		 $stmt ="UPDATE general_jr SET  service_name='$service_name', details='$details', total_time='$total_time', assigned_personnel='$assigned_personnel', r_materials='$r_materials', r_comments='$r_comments' WHERE jr_number='$jrNumber';";
		
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function addMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO general_jr VALUES('$jrNumber','$service_name','$details','$total_time','$assigned_personnel',NULL,NULL);";
		
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function updateMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "UPDATE general_jr SET  service_name='$service_name', details='$details', total_time='$total_time', assigned_personnel='$assigned_personnel', r_materials=NULL, r_comments=NULL WHERE jr_number='$jrNumber';";
		
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function updateTech($jrNumber,$e_brand,$e_type,$e_par,$e_accesory){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "UPDATE tech_sup SET  e_brand='$e_brand', e_type='$e_type', e_par='$e_par', e_accesory='$e_accesory' WHERE jr_number='$jrNumber';";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function addTech($jrNumber,$e_brand,$e_type,$e_par,$e_accesory){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO tech_sup VALUES('$jrNumber','$e_brand','$e_type','$e_par','$e_accesory');";
		
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function addNetAd($jrNumber,$e_provided,$e_serial){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO net_ad VALUES('$jrNumber','$e_provided','$e_serial');";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	
	public function addRent($jrNumber,$equipment,$monthly_payment,$end_of_contract){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO rent_to_own VALUES('$jrNumber','$equipment','$monthly_payment','$end_of_contract');";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function updateRent($jrNumber,$equipment,$monthly_payment,$end_of_contract){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "UPDATE rent_to_own SET equipment='$equipment', monthly_payment='$monthly_payment', end_of_contract='$end_of_contract' WHERE jr_number='$jrNumber';";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function retrieveListOfAccount(){
		$i=0;
		$users = array();
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM user_account;";
		$result = mysqli_query($con,$stmt);
		
		while($row=mysqli_fetch_assoc($result))
			$users[] = new User($row['USERNAME'], $row['NAME'], $row['UA_LEVEL'], $row['UA_SECTION'],$row['PASSWORD']);
		
		return $users;
	}
	
	public function retrieveJobRequests(){
		$i=0;
		$users = array();
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM job_request;";
		$result = mysqli_query($con,$stmt);
		
		while($row=mysqli_fetch_assoc($result))
			$users[] = new JobRequest($row['JR_NUMBER']);
		
		return $users;
	}
	
	public function getJobRequest($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM job_request where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a;
	}
	
	public function getGeneral($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM general_jr where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a;
	}
	public function getTech($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM tech_sup where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a;
	}
	
	public function getNetad($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM net_ad where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a;
	}
	
	public function removeNetad($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "DELETE FROM net_ad where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
	}
	
	public function getRent($jrNumber){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT * FROM rent_to_own where jr_number='$jrNumber';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a;
	}
	
	public function countJR(){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT count(jr_number) FROM job_request;";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a[0];
	}
	
	
	public function countAccounts(){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT count(*) FROM user_account;";
		$a=mysqli_fetch_array(mysqli_query($con,$stmt));
		echo $a[0];
		return $a[0];
	}
	public function retrievePassword($username){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT password FROM user_account where username like '$username';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a[0];
	}
	
	public function retrieveName($username){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT name FROM user_account where username like '$username';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a[0];
	}
	public function retrieveLevel($username){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT ua_level FROM user_account where username like '$username';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a[0];
	}
	public function retrieveSection($username){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT ua_section FROM user_account where username like '$username';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		return $a[0];
	}
	
	public function checkAvailableUsername($username){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "SELECT count(*) FROM user_account where username like '$username';";
		$result = mysqli_query($con,$stmt);
		$a=mysqli_fetch_array($result);
		if($a[0]==0)
			return true;
		else return false;
	}
	
	public function addStatusLog($jrNumber,$reason,$status){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO status_log VALUES(date('timestamp'),'$reason','$status','$jrNumber');";
		$a=mysqli_query($con,$stmt);
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
	
	public function addEditLog($jrNumber,$comment){
		$con = mysqli_connect("localhost","root","","itcbs_db");
		$stmt = "INSERT INTO edit_log VALUES(date('timestamp'),'$comment', '$jrNumber');";
		$a=mysqli_query($con,$stmt) or die(mysql_error());
		mysqli_close($con);
		if($a)
			return 1;
		else return 0;
	}
}
?>
