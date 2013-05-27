<?php
	include("sessionGet.php");
	
	$sessionUsername=getSessionUsername();
	$sessionName=getSessionName();
	$sessionUserType=getSessionUserType();
	$sessionUserSection=getSessionUserSection();
	
	if($sessionUserType=="Executive" || $sessionUserType=="Manager")
		header('Location: home_exec_manager.php');
	else
		header('Location: job_request_user.php');
?>