<form>
	<input type="submit" value="Filter off" id="revert" name="revert"/>
</form>
<?php
	echo '<link rel="stylesheet" type="text/css" href="css/style.css" />';
	
	if(isset($_POST['goJr'])){
		if($sessionUserType=="Executive"){
			$listOfJR = $user->retrieveListOfJRfromJRNum($_POST['srJrNum'], $sessionUserSection);
		}
		else{
			$listOfJR = $user->retrieveListOfJRfromJRNum_exec($_POST['srJrNum']);
		}
		unset($_POST['goJr']);
	}
	else if(isset($_POST['goFrTo'])){
		if($sessionUserType=="Executive"){
			$listOfJR = $user->retrieveListOfJRfromDate_exec($_POST['from'], $_POST['to']);
		}
		else{
			$listOfJR = $user->retrieveListOfJRfromDate($_POST['from'], $_POST['to'], $sessionUserSection);
		}
		unset($_POST['goFrTo']);
	}
	
	else if($sessionUserType=="Executive"){
		if(isset($_POST['perSection'])){
			$section=$_POST['section'];
			$listOfJR = $user->retrieveListOfJR($section);
		}else
			$listOfJR = $user->retrieveListOfJR_exec();
	}
	else{
		if(isset($_POST['stat'])){
			$status=$_POST['status'];
			$listOfJR = $user->retrieveListOfJRStatus($sessionUserSection,$status);
		}else{
			if(isset($_POST['clientOffice'])){
				$office=$_POST['office'];
				$listOfJR = $user->retrieveListOfJROffice($sessionUserSection,$office);
			}else $listOfJR = $user->retrieveListOfJR($sessionUserSection);
		}
	}
	
	echo '<div id="scrollable">';
	echo '<table class="jr" border="1px solid" cellspacing="0">';
	echo '<tr>';
		echo '<th class="titleTable" colspan=7>';
			echo "Job Requests";
		echo '</th>';
	echo '</tr>';
	echo '<tr align="center">';
		echo '<td>';
			echo "<b>JR No.</b>";
		echo '</td>';
		echo '<td>';
			echo "<b>Date Created</b>";
		echo '</td>';
		echo '<td>';
			if($sessionUserSection!="Rent to Own")
				echo "<b>Client Office</b>";
			else echo "<b>Client Name</b>";
		echo '</td>';
		echo '<td>';
			echo "<b>Total Amount</b>";
		echo '</td>';
		echo '<td>';
			echo "<b>Status</b>";
		echo '</td>';
		if($sessionUserType!="Executive"){
			echo '<td>';
				echo"Edit";
			echo '</td>';
		}
		echo '<td>';
			echo 'View';
		echo '</td>';
		
		
	echo '</tr>';
	for($i=0 ; $i<count($listOfJR) ; $i++){
		echo '<tr>';
			echo '<td>';
				echo '<form method="post" action="editJR.php">';
					echo $listOfJR[$i]->getJrNum();
					echo '<input type="hidden" name="jrnum'.$i.'" value="'.$listOfJR[$i]->getJrNum().'">';
			echo '</td>';
			echo '<td>';
				echo $listOfJR[$i]->getDateCrt();
			echo '</td>';
			echo '<td>';
				if($sessionUserSection!="Rent to Own")
					echo $listOfJR[$i]->getClientOffice();
				else echo $listOfJR[$i]->getClientName();
			echo '</td>';
			echo '<td>';
				echo $listOfJR[$i]->getAmount();
			echo '</td>';
			echo '<td>';
				echo $listOfJR[$i]->getStatus();
			echo '</td>';
			if($sessionUserType!="Executive"){
				echo '<td>';
						echo '<input type="submit" value="Edit" name="edit'.$i.'"/>';
				echo '</td>';
			}
			echo '</form>';
			echo '<td>';
				echo '<form method="post" action="produceJR.php">';
					echo '<input type="submit" value="View" name="view'.$i.'"/>';
					echo '<input type="hidden" name="jrNum'.$i.'" value="'.$listOfJR[$i]->getJrNum().'">';
				echo '</form>';
			echo '</td>';
		
		echo '</tr>';
	}
	echo '</table></div>';
?>



<?php
	if(isset($_POST['revert'])){
		$listOfJR = $user->retrieveListOfJR($sessionUserSection);
		unset($_POST['revert']);
	}
?>
