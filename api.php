<?php
require_once 'Data.php';

class Api {
	private static $db = null;
	private $settings;
	public function __construct() {
		if (self::$db === null) {
			$this->dbConnect ();
		}
		return self::$db;
	}
	
	/**
	 * Connect to the database, select the database using the database link and database name
	 */
	private function dbConnect() {		
		$this->settings = Configuration::getInstance()->get();
		$dsn = $this->settings['db_type'] .":dbname=" . $this->settings['db_name'] .";host=" . $this->settings['host'];
		try {
			self::$db = new PDO ( $dsn, $this->settings['db_user'], $this->settings['db_pass'], array (PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'') );
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
		if (method_exists ( $this, $action )) {
			$this->$action();
		} else {
			error_log('Error executing: '.$action);
		}
		
	}
	
	/**
	 * Reads data from the input file,and inserts into db.
	 * 
	 */
	private function save() {
				
		$data = new Data();
		$dataInput = $data->getStaffRecords();
		$sqlDep = "INSERT INTO departments (department_name,contact_name,contact_emailaddress) VALUES (:department_name, :contact_name, :contact_emailaddress)";
		$sqlCon = "INSERT INTO contacts (first_name,last_name,email_address,gender,department_id) VALUES (:first_name, :last_name, :email_address, :gender, :department_id)";

		$stmtDep = self::$db->prepare($sqlDep);
		$stmtCon = self::$db->prepare($sqlCon);
		
		$removedData = $this->removeExistingData();

		foreach($dataInput as $line){
			list($firstName, $lastName, $emailAddress, $gender, $departmentName, $contactName, $contactEmailaddress) = explode("\t",$line);
			try{			
				self::$db->beginTransaction();
				$stmtDep->execute(array(':department_name'=>$departmentName,':contact_name'=>$contactName,':contact_emailaddress'=>$contactEmailaddress));
				$departmentId = self::$db->lastInsertId();
				$stmtCon->execute(array(':first_name' => $firstName, ':last_name'=>$lastName, ':email_address' => $emailAddress, ':gender' => $gender, ':department_id'=>$departmentId));
				self::$db->commit();							
			}catch(PDOException $e){
				error_log($e);
			}
		}
	}
	
	private function removeExistingData(){
		try{
			self::$db->beginTransaction();
			$sql = "TRUNCATE contacts";
			$contRows = self::$db->exec($sql);
			$sql = "DELETE FROM departments";
			$depRows = self::$db->exec($sql);
			self::$db->commit();
			return array('contRows'=>$contRows,'depRows'=>$depRows);
		}catch(PDOException $e){
			error_log($e);
			die;
		}
	}
}

$api = new Api ();
$api->processApi ();

?>