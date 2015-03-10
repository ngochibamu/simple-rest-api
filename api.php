<?php

require_once 'Rest.inc.php';

class Api extends REST {
	const DB_HOST = "localhost";
	const DB_USER = "silver";
	const DB_PASS = "treesilver";
	const DB_NAME = "silvertree";
	const DB_TYPE = "mysql";
	private static $db = null;
	public function __construct() {
		parent::__construct ();
		if (self::$db === false) {
			$this->dbConnect ();
		}
		return self::$db;
	}
	
	/**
	 * Connect to the database, select the database using the database link and database name
	 */
	private function dbConnect() {
		$dsn = DB_TYPE .":dbname=" . DB_NAME.";host=" .DB_HOST;
		try {
			self::$db = new PDO ( $dsn, DB_USER, DB_PASS, array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'') );
			self::$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			 print_r($e->errorInfo);
		}
	}
	/**
	 * public method available exposed to consumers of this api.Resolves the action
	 * to be executed from the request.
	 */
	public function processApi() {
		$action = strtolower ( trim ( str_replace ( "/", "", $_REQUEST ["request"] ) ) );
		var_dump($action);
		if (method_exists ( $this, $action )) {
			$this->action;
		} else {
			$this->response ('', 404 );
		}
		var_dump ( $action );
	}
	private function save() {
		if (strtolower($this->get_request_method) != "POST") {
			$this->response ( '', 406 );
		}
		$data = new Data();
		try{			
			$sql = "INSERT INTO departments (department_name,contact_name,contact_emailaddress) VALUES (:department_name, :contact_name, :contact_emailaddress)";		
			$stmt = self::$db->prepare($sql);
			$resultDepart = $stmt->exec(array(':department_name'=>$departmentName, ':contact_name'=>$contactName, ':contact_emailaddress'=>$contactEmailaddress));
			
			$sql = "INSERT INTO contacts (first_name,last_name,email_address,gender,department_id) VALUES (:first_name, :last_name, :email_address, :gender, :department_id)";
			$stmt = self::$db->prepare($sql);
			$resultContacts = $stmt->exec(array(':first_name' => $firstName, ':last_name'=>$lastName, ':email_address' => $emailAddress, ':gender' => $gender, ':department_id'=>$departmentId));
			
		}catch(PDOException $e){
			error_log($e);
		}
	}
	
	private function removeExistingData(){
		$sql = "DELETE FROM departments";
		$numrows = self::$db->exec($sql);
		$sql = "DELETE FROM contacts";
		$numrows = self::$db->exc($sql);
	}
}

$api = new Api ();
$api->processApi ();

?>