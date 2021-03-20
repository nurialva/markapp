<?php

     class product {

          public $id;
          public $param;

          private $pdo;

          public function __construct($pdo) {
               $this->pdo = $pdo;
          }

		public function delete () {

			$stmt = $this -> pdo -> prepare (
				"delete from product where id = ?"
			);
			$stmt -> bindparam ( 1 , $this -> id );
			$stmt -> execute ();

			return "deleted";
		}


		public function edit () {

			$param = (object) $this -> param;
			$stmt = $this -> pdo -> prepare (
				"update product set in_stock = ? where id = ?"
			);
			$stmt -> bindparam ( 1 , $param -> in_stock );
			$stmt -> bindparam ( 2 , $param -> id );
			$stmt -> execute ();

			return "updated";
		}
          
          public function add () {
          
			$param = (object) $this -> param;
          	$id = mt_rand ();
          	$stmt = $this -> pdo -> prepare (
          		"insert into product ( id, name, img_url, shop_link, price, catalog_id, description ) values ( ? , ? , ? , ? , ? , ? , ? )"
          	);
          	$stmt -> bindparam ( 1 , $id );
          	$stmt -> bindparam ( 2 , $param -> name );
          	$stmt -> bindparam ( 3 , $param -> img_url );
          	$stmt -> bindparam ( 4 , $param -> shop_link );
          	$stmt -> bindparam ( 5 , $param -> price );
          	$stmt -> bindparam ( 6 , $param -> catalog_id );
          	$stmt -> bindparam ( 7 , $param -> description );
          	$stmt -> execute ();
          	return "added";
          	
          }


          public function get () {

          	$stmt = $this -> pdo -> prepare (
          		"select * from product " . $this -> param
          	);
          	$stmt -> execute ();
          	return $stmt -> fetchAll ();
          	
          }

          public function get_by_id () {
          
          	$stmt = $this -> pdo -> prepare (
          		"select * from product where id = ? " 
          	);
          	$stmt -> bindparam ( 1 , $this -> id );
          	$stmt -> execute ();
          	return $stmt -> fetchObject ();
          	
          }

          

          

	}
