<?php
	include("functions.php");
	
	define( '_JEXEC', 1 );
	define('JPATH_BASE', dirname(__FILE__) );
	define( 'DS', DIRECTORY_SEPARATOR );
	require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
	require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
	$mainframe = JFactory::getApplication('site');
	
	
	function getSessionName(){
		$user = JFactory::getUser();
		return $user->name;
	}
	
	function getSessionUsername(){
		$user = JFactory::getUser();
		return $user->username;
	}

	function getSessionUserType(){
	
		$sessionUser = new databaseManager;
		
		$usergroup = array();
		
		$user = JFactory::getUser();
		foreach ($user->groups as $key => $value){
			$a = $key;
			$usergroup[] = $sessionUser->retrieveUserGroup($a);
		}
		
		return $usergroup[0];
	
	}
	
	function getSessionUserSection(){
	
		$sessionUser = new databaseManager;
		
		$usergroup = array();
		
		$user = JFactory::getUser();
		foreach ($user->groups as $key => $value){
			$a = $key;
			$usergroup[] = $sessionUser->retrieveUserGroup($a);
		}
		
		return $usergroup[1];
	
	}

	$typeFlag=0;
	$sectionFlag=0;
	
	$usergroup = array();
		
	$user = JFactory::getUser();
	foreach ($user->groups as $key => $value){
		$a = $key;
		$usergroup[] = $a;
	}
	
	
	$sessionUserType=$usergroup[0];
	$sessionUserSection=$usergroup[1];
	
	
	
	if($sessionUserType==15)
		$typeFlag=1;
	if($sessionUserType==6)
		$typeFlag=2;
	if($sessionUserType==11)
		$typeFlag=3;
		
	if($typeFlag==0){
		header('Location: wrongPrivilege.php');
	}else{
		if($typeFlag==1){
			if($sessionUserSection==16)
				$sectionFlag=1;
		}
		if($typeFlag==2){
			if($sessionUserSection==19 || $sessionUserSection==7 || $sessionUserSection==18 || $sessionUserSection==17 || $sessionUserSection==10)
				$sectionFlag=2;
		}
		if($typeFlag==3){
			if($sessionUserSection==12 || $sessionUserSection==14 || $sessionUserSection==20 || $sessionUserSection==13 || $sessionUserSection==21)
				$sectionFlag=3;
		}
	}
	if($sectionFlag==0){
		header('Location: wrongPrivilege.php');
	}
	

	
	
?>