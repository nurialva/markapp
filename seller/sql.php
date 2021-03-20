<?php
	/**
	 * parent file to prevent
	 * direct access to children file
	 */
	define("PARENT_FILE", dirname(__FILE__));
	require_once 'config.php';

	/**
	 * generate connection from /src/ 
	 * with autoload class
	 */

     $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     function do_something ( $pdo , $order ) {
          $stmt = $pdo -> prepare (
               $order
          );
          $stmt -> execute ();
     }

     if ( $pdo ) {
          /*
          ** initiate database table
          */
          $schemes = array (
		    "product" => "CREATE TABLE IF NOT EXISTS product (
			    id varchar(100) not null primary key,
			    name varchar(500) not null,
			    description text not null,
			    img_url varchar(500) not null,
			    in_stock int(1) not null default 1,
			    price varchar(20) not null,
			    catalog_id int(11) not null,
			    date timestamp default current_timestamp
		    )",
		    "catalog" => "CREATE  TABLE IF NOT EXISTS catalog (
			    id int(11) not null primary key auto_increment,
			    name varchar(100) not null,
			    date timestamp default current_timestamp
		    )"
			
          );

          /*
          ** initiate user admin
          */

          foreach ( $schemes as $key =>  $value ) {
               do_something ( $pdo , $value );
          }


		echo "<br />Database connected";
     }
