<?php
class database {
     public $pdo;

     public function connect() {
          $this->pdo = null;
          try{ $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS); }catch(PDOException $exception){ echo "Connection error: " . $exception->getMessage(); }
          return $this->pdo;
     }

	public function show_tables () {

		$stmt = $this -> pdo -> prepare (
			"show tables"
		);
		$stmt -> execute ();
		return $stmt -> fetchAll ();
	}

	public function show_record () {
		$stmt = $this -> pdo -> prepare (
			"select * from " . $this -> param
		);
		$stmt -> execute ();
		return $stmt -> fetchAll ();
		
	}

	public function sync_data () {
		$db_user = "b3c228063e0685";
		$db_pass = "b945422f";
		$db_host = "us-cdbr-east-03.cleardb.com";
		$db_name = "heroku_f21600868659a96";
	
          $rd = null;
          try { 
          $rd = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name , $db_user, $db_pass ); 
          }
          catch(PDOException $exception){ echo "Connection error: " . $exception->getMessage(); 
          }

          

		
	}




}
?>
