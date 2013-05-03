<?php
/*constructors, setters and getters*/

class User
{
	private $username;
	private $password;
	private $name;
	private $level;
	private $section;
	
	function __construct($username, $name, $level,$section, $password)
	{
		$this->username=$username;
		$this->password=$password;
		$this->name=$name;
		$this->level=$level;
		$this->section=$section;
	}
	
	public function setUsername($username)
	{
		$this->username=$username;
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setPassword($password)
	{
		$this->password=$password;
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function setName($name)
	{
		$this->name=$name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setLevel($level)
	{
		$this->level=$level;
	}
	
	public function getLevel()
	{
		return $this->level;
	}
	
	public function setSection($section)
	{
		$this->section=$section;
	}
	public function getSection()
	{
		return $this->section;
	}
}

class JobRequest
{
	private $jrNumber;
	
	function __construct($jrNumber)
	{
		$this->jrNumber=$jrNumber;
	}
	
	public function getjrNumber()
	{
		return $this->jrNumber;
	}
	
	
}?>
