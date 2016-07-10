<?php

define('MYSQL_SERVER','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','');
define('MYSQL_DB','project');



class DB {
		
        private $connect = false;
		private $data = array();

		public function __construct() {
			$this->connect = new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
			
			$this->connect->connect_error;
		}

		public function qrySelect($sql) {
			
            $qry = $this->connect->query($sql);
                
			if($qry->num_rows > 0) {
                //$test = 1; 
                //return $test;
				while($row = $qry->fetch_assoc()) {
					$this->data["id"] = $row['id'];
                    $this->data["login"] = $row['login'];
                    $this->data["email"] = $row['email'];
                    
				}
			} else {
				$this->data["id"] = null;
            //$test = 0;    
            //return $test;
			}
            return $this->data;
			$this->connect->close();
            
		}

		public function qryFire($sql) {
		
				$this->connect->query($sql);
				
		
        }
            
        public function escapeString($string){
            $escapedString = $this->connect->real_escape_string($string);
            return $escapedString;
        }
			
			
		

}


?>