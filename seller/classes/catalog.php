<?php

     class catalog {

          private $pdo;

          public $id;
          public $param;

          public function __construct($pdo) {
               $this -> pdo = $pdo;
          }

          public function get () {
          	$stmt = $this -> pdo -> prepare (
          		"select * from catalog " . $this -> param
          	);
          	$stmt -> execute ();
          	return $stmt -> fetchAll ();
          }

          public function add () {
          
          	$id = mt_rand ();
          	$stmt = $this -> pdo -> prepare (
          		"insert into catalog (name) values (?)"
          	);
          	$stmt -> bindparam ( 1 , $this -> param );
          	$stmt -> execute ();
          	return "added";
          	
          }

          public function delete () {

          	$stmt = $this -> pdo -> prepare (
          		"select * from product where catalog_id = ? "
          	);
          	$stmt -> bindparam ( 1 , $this -> id );
          	$stmt -> execute ();
          	$res_data = $stmt -> fetchAll ();
          	if ( !empty ( $res_data ) ) {
          		return "can't edit, the catalog being used!";
          	}
          	else {
          		$stmt = $this -> pdo -> prepare (
          			"delete from catalog where id = ? "
          		);
          		$stmt -> bindparam ( 1 , $this -> id );
          		$stmt -> execute ();
          		return "deleted!";
          	}
          	
          }


	}
