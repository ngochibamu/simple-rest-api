<?php 

	require_once 'Rest.inc.php';
	
	class Api extends REST {
		const DB_SERVER = "localhost";
		const DB_USER="silver";
		const DB_PASSWORD="treesilver";
		const DB="silvertree";
		
		private $db = null;
		
		public function __construct(){
			var_dump($_REQUEST);
			parent::__construct();
			$this->dbConnect();
		}
		
		/**
		 * Connect to the database, select the database using the database link and database name
		 */
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD);
			if($this->db){
				mysql_select_db($DB, $this->db);
			}
		}
		/**
		 * public method available exposed to consumers of this api.Resolves the action 
		 * to be executed from the request.
		 */
		public function processApi(){
			$action = strtolower(trim(str_replace("/", "", $_REQUEST["request"])));
			if(method_exists($this, $action)){
				$this->action;
			}else{
				$this->response("", 404);
			}
			var_dump($action);
		}
		
	}
	
	$api = new Api(); 
	$api->processApi();
	
?>