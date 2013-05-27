<?php
include("classes.php");

//connect to the database
$con = mysqli_connect("localhost","root","","itcbs_db");

	class databaseManager{	
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
		public function deleteAccount($username){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "DELETE FROM user_account where username like '$username';";
			$result = mysqli_query($con,$stmt);
			
			return $result;
		}
		
		public function editAccount($username, $password, $name, $level, $section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "UPDATE user_account SET UA_LEVEL='$level', NAME='$name', UA_SECTION='$section' WHERE USERNAME='$username'";
			$result = mysqli_query($con,$stmt);
			return $result;
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
		
		
		public function retrieveUserGroup($gid){
			switch($gid){
				case 2:
					$userGroup="Registered";
					break;
				case 6:
					$userGroup="Manager";
					break;
				case 7:
					$userGroup="Network Ad";
					break;
				case 8:
					$userGroup="Administrator";
					break;
				case 10:
					$userGroup="Tech Support";
					break;
				case 11:
					$userGroup="User(Encoder)";
					break;
				case 12:
					$userGroup="MIS";
					break;
				case 13:
					$userGroup="System Ad";
					break;
				case 14:
					$userGroup="Network Ad";
					break;
				case 15:
					$userGroup="Executive";
					break;
				case 16:
					$userGroup="Director";
					break;
				case 17:
					$userGroup="System Ad";
					break;
				case 18:
					$userGroup="Rent to Own";
					break;
				case 19:
					$userGroup="MIS";
					break;
				case 20:
					$userGroup="Rent to Own";
					break;
				case 20:
					$userGroup="Tech Support";
					break;
				
			}
			return $userGroup;
		}
	}

	class functionalityManager{

		public function editAccount($username, $password, $name, $level, $section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "UPDATE user_account SET PASSWORD='$password', NAME='$name' WHERE USERNAME='$username' AND SECTION='$section'";
			$result = mysqli_query($con,$stmt);
			$_SESSION['password'] = $password;
			$_SESSION['ua_name'] = $name;
			return $result;
		}
		
		public function retrieveListOfJRfromJRNum($jrNum,$section){
			$i=0;
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE jr_number='$jrNum' AND SECTION='$section'";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function retrieveListOfJRfromDate($from, $to, $section){
			$i=0;
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE date_created>='$from' AND date_created<='$to' AND SECTION='$section' ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function retrieveListOfJRfromJRNum_exec($jrNum){
			$i=0;
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE jr_number='$jrNum' ";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function retrieveListOfJRfromDate_exec($from, $to){
			$i=0;
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE date_created>='$from' AND date_created<='$to' ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function retrieveListOfJR($section){
	
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE section='$section' ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function retrieveListOfJROffice($section,$office){
	
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE section='$section' and client_office='$office'ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		public function retrieveListOfJRStatus($section,$status){
	
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request WHERE section='$section' and status='$status' ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		
		
		public function retrieveMaxJRNum(){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT max(jr_main_number) FROM job_request;";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		
		public function countJR($section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT count(jr_number) FROM job_request WHERE section='$section';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		public function countJRAll(){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT count(jr_number) FROM job_request;";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		
		public function retrieveListOfJR_exec(){
			$i=0;
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM job_request ORDER BY date_created DESC;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new JobRequest($row['JR_NUMBER'], $row['DATE_CREATED'], $row['DATE_ACCOMPLISHED'], $row['DATE_BILLED'], $row['DATE_PAID'], $row['CLIENT_OFFICE'], $row['CLIENT_NAME'], $row['CLIENT_EMAIL'], $row['CLIENT_TELNUM'], $row['CLIENT_DESIGNATION'], $row['PROBLEM'], $row['SERVICE_TYPE'], $row['STATUS'], $row['BILL_STATUS'], $row['PAYMENT_STATUS'], $row['UA_USERNAME'], $row['SOA_NUMBER'], $row['AMOUNT']);
			
			return $JR;
		}
		
		public function addJobRequest($lastNum, $jr_number, $section, $date_created, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem, $service_type, $status, $bill_status, $payment_status, $date_accomplished, $date_billed, $date_paid, $ua_username, $soa_number, $amount){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO job_request VALUES('$lastNum', '$jr_number','$section','$date_created','$client_office','$client_name','$client_email','$client_telnum','$client_designation','$problem','$service_type','$status','$bill_status','$payment_status','$date_accomplished','$date_billed','$date_paid','$ua_username',NULL,'$amount');";
			
			$a=mysqli_query($con,$stmt);
			$b=mysqli_error($con);
			mysqli_close($con);
			if($a)
				return true;
			else{ 
				echo $b;
				return false;
			}
		}
		
		public function updateJobRequest($jrNumber, $status, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			
			$stmt = "UPDATE job_request SET STATUS='$status', CLIENT_OFFICE='$client_office', CLIENT_NAME='$client_name', CLIENT_EMAIL='$client_email', CLIENT_TELNUM='$client_telnum', CLIENT_DESIGNATION='$client_designation', PROBLEM='$problem' WHERE JR_NUMBER='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			$b=mysqli_error($con);
			mysqli_close($con);
			if($a){
				if($status="Done"){
					$stmt = "UPDATE job_request SET DATE_ACCOMPLISHED=current_date WHERE JR_NUMBER='$jrNumber';";
					$a=mysqli_query($con,$stmt);
				}
				return true;
			}else{ 
				echo $b;
				return false;
			}
		}
		
		public function addGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount,$r_materials,$r_comments){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO general_jr VALUES('$jrNumber','$service_name','$details','$total_time','$assigned_personnel','$total_amount', '$r_materials', '$r_comments');";
			$a=mysqli_query($con,$stmt);
			mysqli_close($con);
			if($a)
				return 1;
			else return 0;
		}
		
		public function updateGeneral($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount,$r_materials,$r_comments){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt ="UPDATE general_jr SET SERVICE_NAME='$service_name', DETAILS='$details', TOTAL_TIME='$total_time', ASSIGNED_PERSONNEL='$assigned_personnel', TOTAL_AMOUNT='$total_amount', R_MATERIALS='$r_materials', R_COMMENTS='$r_comments' WHERE JR_NUMBER='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			$stmt = "UPDATE job_request SET AMOUNT='$total_amount', SERVICE_TYPE='$service_name' WHERE JR_NUMBER='$jrNumber'";
			$b=mysqli_query($con,$stmt);
			mysqli_close($con);
			if($a && $b)
				return true;
			else{ 
				return false;
			}
		}
		
		public function addMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO general_jr VALUES('$jrNumber','$service_name','$details','$total_time','$assigned_personnel','$total_amount',NULL,NULL);";
			$a=mysqli_query($con,$stmt);
			mysqli_close($con);
			if($a)
				return 1;
			else return 0;
		}
		
		public function updateMIS($jrNumber,$service_name,$details,$total_time,$assigned_personnel,$total_amount){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			echo $jrNumber. " | " .$service_name. " | " .$details. " | " .$total_time. " | " .$assigned_personnel. " | " .$total_amount;
			$stmt = "UPDATE general_jr SET SERVICE_NAME='$service_name', DETAILS='$details', TOTAL_TIME='$total_time', ASSIGNED_PERSONNEL='$assigned_personnel', TOTAL_AMOUNT='$total_amount', R_MATERIALS=NULL, R_COMMENTS=NULL WHERE JR_NUMBER='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			$stmt = "UPDATE job_request SET AMOUNT='$total_amount', SERVICE_TYPE='$service_name' WHERE JR_NUMBER='$jrNumber'";
			$b=mysqli_query($con,$stmt);
			mysqli_close($con);
			if($a && $b)
				return true;
			else{ 
				return false;
			}
		}
		
		public function addTech($jrNumber,$e_newold,$e_brand,$e_type,$e_par,$e_accesory,$released_status){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO tech_sup VALUES('$jrNumber','$e_newold','$e_brand','$e_type','$e_par','$e_accesory', '$released_status',NULL,NULL);";
			$a=mysqli_query($con,$stmt);
			//mysqli_close($con);
			$b=mysqli_error($con);
			echo $b;
			if($a)
				return 1;
			else return 0;
		}
		
		public function updateTech($jrNumber,$e_newold,$e_brand,$e_type,$e_par,$e_accesory,$released_status,$released_by,$released_date){
			$con = mysqli_connect("localhost","root","","itcbs_db");

			$stmt = "UPDATE tech_sup SET NEWOLD='$e_newold', E_BRAND='$e_brand', E_TYPE='$e_type', E_PAR='$e_par', E_ACCESORY='$e_accesory', RELEASED_STATUS='$released_status', RELEASED_BY='$released_by', RELEASED_DATE='$released_date' WHERE JR_NUMBER='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			$b=mysqli_error($con);
			//mysqli_close($con);
			echo $b;
			if($a)
				return true;
			else{ 
				echo $b;
				return false;
			}
		}
		
		public function addNetAd($jrNumber,$e_provided1,$e_serial1,$e_provided2,$e_serial2,$e_provided3,$e_serial3,$e_provided4,$e_serial4,$e_provided5,$e_serial5){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO net_ad VALUES('$jrNumber','$e_provided1','$e_serial1','$e_provided2','$e_serial2','$e_provided3','$e_serial3','$e_provided4','$e_serial4','$e_provided5','$e_serial5');";
			$a=mysqli_query($con,$stmt);
			$b=mysqli_error($con);
			mysqli_close($con);
			if($a)
				return true;
			else{ 
				echo $b;
				return false;
			}
		}
		
		public function retrieveListOfJR_renttoown(){
			$JR = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM rent_to_own;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$JR[] = new RentToOwn($row['JR_NUMBER'], $row['EQUIPMENT'], $row['TOTAL_AMOUNT'], $row['MONTHLY_PAYMENT'], $row['END_OF_CONTRACT']);
			
			return $JR;
		}
		
		public function getAmount($month,$section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT count(jr_number) from job_request WHERE MONTHNAME( date_created ) LIKE  '$month' and section='$section';";
			//$stmt = "SELECT SUM( amount ) FROM job_request WHERE MONTHNAME( date_created ) LIKE  '$month';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		
		public function getAmountPerSection($month,$section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			if($section!="Rent to Own"){
				$stmt = "SELECT SUM( amount ) FROM job_request WHERE MONTHNAME( date_paid ) LIKE  '$month' AND YEAR( date_paid ) = YEAR( current_date ) and section='$section';";
			}//$stmt = "SELECT SUM( amount ) FROM job_request WHERE MONTHNAME( date_created ) LIKE  '$month' and section='$section';";
			else{
				$stmt = "SELECT sum(monthly_payment) from rent_to_own_monthly where MONTHNAME( date_paid ) LIKE  '$month' AND YEAR( date_paid ) = YEAR( current_date );";
			}
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		
		public function getAmountSection($section){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT SUM( amount ) FROM job_request WHERE section='$section' AND YEAR( date_paid ) = YEAR( current_date );";
			//$stmt = "SELECT SUM( amount ) FROM job_request WHERE service_type='$section';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a[0];
		}
		
		public function addRent($jrNumber,$equipment,$total_amount,$terms,$monthly_payment,$start_of_contract,$end_of_contract){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO rent_to_own VALUES('$jrNumber','$equipment',$total_amount,'$terms',$monthly_payment,'$start_of_contract','$end_of_contract');";
			$a=mysqli_query($con,$stmt);
			
			$stmt = "UPDATE job_request SET AMOUNT=$total_amount WHERE JR_NUMBER='$jrNumber'";
			$b=mysqli_query($con,$stmt);
			if($b && $a)
				return true;
			else{ 
				return false;
			}
			
		}
		public function addRentMonthly($jrNumberMonthly,$jrNumber,$client_name,$monthly_payment,$start_of_contract,$bill_status,$payment_status,$count){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO rent_to_own_monthly VALUES('$jrNumberMonthly','$jrNumber','$client_name',DATE_ADD('$start_of_contract', INTERVAL $count MONTH),NULL,$monthly_payment,'$bill_status',NULL,'$payment_status',NULL,NULL,$count);";
			$a=mysqli_query($con,$stmt);
			
		}
		
		public function updateRent($jrNumber,$equipment,$total_amount,$terms,$monthly_payment,$end_of_contract){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "UPDATE rent_to_own SET equipment='$equipment', total_amount=$total_amount, terms='$terms',monthly_payment=$monthly_payment, start_of_contract='$start_of_contract',end_of_contract='$end_of_contract' WHERE jr_number='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			
			$stmt = "UPDATE job_request SET AMOUNT=$total_amount WHERE JR_NUMBER='$jrNumber'";
			$b=mysqli_query($con,$stmt);
			mysqli_close($con);
			if($a && $b)
				return true;
			else{ 
				return false;
			}
		}
		
		public function updateMonthly($jrNumber,$client_name,$monthly_payment){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "UPDATE rent_to_own_monthly SET client_name='$client_name',monthly_payment=$monthly_payment
				WHERE jr_parent_number='$jrNumber';";
			$a=mysqli_query($con,$stmt);
			
			$stmt = "SELECT soa_number from rent_to_own_monthly 
				WHERE jr_parent_number='$jrNumber';";
			$result = mysqli_query($con,$stmt);
			while($row=mysqli_fetch_assoc($result)){
				$soaNum=$row['soa_number'];
				$stmt = "UPDATE soa SET client_name='$client_name',total_amount=$monthly_payment
					WHERE soa_number=$soaNum;";
				$a=mysqli_query($con,$stmt);
			}
		
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
		
		public function updateNetad($jrNumber,$e_provided1,$e_serial1,$e_provided2,$e_serial2,$e_provided3,$e_serial3,$e_provided4,$e_serial4,$e_provided5,$e_serial5){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "UPDATE net_ad SET EQUIP1='$e_provided1', EQUIP2='$e_provided2', EQUIP3='$e_provided3', EQUIP4='$e_provided4', EQUIP5='$e_provided5' WHERE jr_number='$jrNumber';";
			$result = mysqli_query($con,$stmt);
			$stmt = "UPDATE net_ad SET SERIAL1='$e_serial1', SERIAL2='$e_serial2', SERIAL3='$e_serial3', SERIAL4='$e_serial4', SERIAL5='$e_serial5' WHERE jr_number='$jrNumber';";
			$result = mysqli_query($con,$stmt);
		}
		
		public function getRent($jrNumber){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM rent_to_own where jr_number='$jrNumber';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a;
		}
		
		public function updateEditLog($jrNumber, $comments, $date, $user){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO edit_log VALUES('$date','$comments','$jrNumber', '$user');";
			$result = mysqli_query($con,$stmt);
		}
		
		public function updateStatusLog($jrNumber, $date, $reason, $new_status, $old_status, $user){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "INSERT INTO status_log VALUES('$date','$reason', '$old_status', '$new_status', '$jrNumber', '$user');";
			$result = mysqli_query($con,$stmt);
		}
		
		public function retrieveListOfExpenses(){
			$expenses = array();
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM expense_log;";
			$result = mysqli_query($con,$stmt);
			
			while($row=mysqli_fetch_assoc($result))
				$expenses[] = new Expenses($row['DATE_ACCUMULATED'], $row['AMOUNT'], $row['DETAILS']);
			
			return $expenses;
		}
		
		public function countExpenses(){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT count(*) FROM expense_log;";
			$a=mysqli_fetch_array(mysqli_query($con,$stmt));
			return $a[0];
		}
		
		public function getExpenses($date){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT * FROM expense_log where date_accumulated='$date';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a;
		}
		
		public function dateReleased($jrNumber){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT released_date as date from tech_sup where jr_number='$jrNumber' and released_status='Released';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a['date'];
		}
		
		public function releasedBy($jrNumber){
			$con = mysqli_connect("localhost","root","","itcbs_db");
			$stmt = "SELECT released_by as rel from tech_sup where jr_number='$jrNumber' and released_status='Released';";
			$result = mysqli_query($con,$stmt);
			$a=mysqli_fetch_array($result);
			return $a['rel'];
		}
	}
?>
