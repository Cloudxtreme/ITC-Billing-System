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
	private $jr_number;
	private $date_created;
	private $date_accomplished;
	private $date_billed;
	private $date_paid;
	private $client_office;
	private $client_name;
	private $client_email;
	private $client_telnum;
	private $client_designation;
	private $problem;
	private $service_type;
	private $status;
	private $bill_status;
	private $payment_status;
	private $ua_username;
	private $soa_number;
	private $amount;
	
	function __construct($jr_number, $date_created, $date_accomplished, $date_billed, $date_paid, $client_office, $client_name, $client_email, $client_telnum, $client_designation, $problem, $service_type, $status, $bill_status, $payment_status, $ua_username, $soa_number, $amount)
	{
		$this->jr_number=$jr_number;
		$this->date_created=$date_created;
		$this->date_accomplished=$date_accomplished;
		$this->date_billed=$date_billed;
		$this->date_paid=$date_paid;
		$this->client_office=$client_office;
		$this->client_name=$client_name;
		$this->client_email=$client_email;
		$this->client_telnum=$client_telnum;
		$this->client_designation=$client_designation;
		$this->problem=$problem;
		$this->service_type=$service_type;
		$this->status=$status;
		$this->bill_status=$bill_status;
		$this->payment_status=$payment_status;
		$this->ua_username=$ua_username;
		$this->soa_number=$soa_number;
		$this->amount=$amount;
	}
	
	public function setJrNum($jr_number)
	{
		$this->jr_number=$jr_number;
	}
	
	public function getJrNum()
	{
		return $this->jr_number;
	}
	
	public function setDateCrt($date_created)
	{
		$this->date_created=$date_created;
	}
	
	public function getDateCrt()
	{
		return $this->date_created;
	}
	
	public function setClientOffice($client_office)
	{
		$this->client_office=$client_office;
	}
	
	public function getClientOffice()
	{
		return $this->client_office;
	}
	public function getClientName()
	{
		return $this->client_name;
	}
	
	public function setAmount($amount)
	{
		$this->amount=$amount;
	}
	
	public function getAmount()
	{
		return $this->amount;
	}
	
	public function setStatus($status)
	{
		$this->status=$status;
	}
	
	public function getStatus()
	{
		return $this->status;
	}	
}

class RentToOwn
{
	private $jr_number;
	private $equipment;
	private $total_amount;
	private $monthly_payment;
	private $end_of_contract;
	
	function __construct($jr_number, $equipment, $total_amount, $monthly_payment, $end_of_contract)
	{
		$this->jr_number=$jr_number;
		$this->equipment=$equipment;
		$this->total_amount=$total_amount;
		$this->monthly_payment=$monthly_payment;
		$this->end_of_contract=$end_of_contract;
	}
	
	public function setJrNum($jr_number)
	{
		$this->jr_number=$jr_number;
	}
	
	public function getJrNum()
	{
		return $this->jr_number;
	}
	
	public function setEquipment($equipment)
	{
		$this->equipment=$equipment;
	}
	
	public function getEquipment()
	{
		return $this->equipment;
	}
	
	public function setAmount($total_amount)
	{
		$this->total_amount=$total_amount;
	}
	
	public function getAmount()
	{
		return $this->total_amount;
	}
	
	public function setMonthlyPayment($monthly_payment)
	{
		$this->monthly_payment=$monthly_payment;
	}
	
	public function getMonthlyPayment()
	{
		return $this->monthly_payment;
	}
	
	public function setEndOfContract($end_of_contract)
	{
		$this->end_of_contract=$end_of_contract;
	}
	
	public function getEndOfContract()
	{
		return $this->end_of_contract;
	}	
}

class Expenses
{
	private $date_accumulated;
	private $amount;
	private $details;
	
	function __construct($date_accumulated, $amount, $details)
	{
		$this->date_accumulated=$date_accumulated;
		$this->amount=$amount;
		$this->details=$details;
	}
	
	public function setDateAcc($date_accumulated)
	{
		$this->date_accumulated=$date_accumulated;
	}
	
	public function getDateAcc()
	{
		return $this->date_accumulated;
	}
	
	public function setAmount($amount)
	{
		$this->amount=$amount;
	}
	
	public function getAmount()
	{
		return $this->amount;
	}
	
	public function setDetails($details)
	{
		$this->details=$details;
	}
	
	public function getDetails()
	{
		return $this->details;
	}
}

?>
