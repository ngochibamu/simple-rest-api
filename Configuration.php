<?php 

	final class Configuration {
		
		protected static $instance = null;
		private static $config = array();
		
		private function __construct(){
			
		}
		
		private function __clone(){
			
		}
		
		public static function getInstance(){
			
			if(!isset(static::$instance)){
				static::$instance = new Configuration();				
			}
			return static::$instance;
		}
		
		public function get($settingsFile = "settings.ini"){
			if(file_exists($settingsFile)){
				static::$config = parse_ini_file($settingsFile);
			}
			var_dump(static::$config);
			return static::$config;
		}
	}
?>