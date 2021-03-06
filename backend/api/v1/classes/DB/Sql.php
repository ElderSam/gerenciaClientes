<?php 

namespace Classes\DB;

class Sql {

	const DBNAME = "bd_system";
	const HOSTNAME = "localhost";
	
	private $conn;

	public function __construct()
	{

		//echo "IP: " . $_SERVER['REMOTE_ADDR'];

		if(($_SERVER['REMOTE_ADDR'] == '::1') || ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')){ //se estiver no Localhost
			
			$username = "root";
			$password = "";

		}else{ //se estiver em um servidor online

			$username = "your_username";
			$password = "your_password";
		}

		$this->conn = new \PDO(
			"mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME, 
			$username,
			$password
		);

	}

	private function setParams($statement, $parameters = array())
	{

		foreach ($parameters as $key => $value) {
			
			$this->bindParam($statement, $key, $value);

		}

	}

	private function bindParam($statement, $key, $value)
	{

		$statement->bindParam($key, $value);

	}

	public function query($rawQuery, $params = array())
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

	}

	public function select($rawQuery, $params = array()):array
	{

		$stmt = $this->conn->prepare($rawQuery);

		$this->setParams($stmt, $params);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}

}

 ?>