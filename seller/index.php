<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>MarkApp Seller </title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" integrity="sha512-UJfAaOlIRtdR+0P6C3KUoTDAxVTuy3lnSXLyLKlHYJlcSU8Juge/mjeaxDNMlw9LgeIotgz5FP8eUQPhX1q10A==" crossorigin="anonymous" />
</head>
<body class='container'>

<?php

	require_once "load.php";
	define("BASE_URL", "http://localhost:8080/");	
	$rp = $_SERVER['REQUEST_URI'];
	
	if ( $rp === '/index.php' || $rp === '/index.php?' || $rp === '/index.php?/' ) {
	
		echo "You are on Index Page!";
		
	}
	else {

		if ( isset ( $_POST['add'] ) ) {
			$product -> param = $_POST;
			$product -> add ();
			header("location:" . BASE_URL );
			
		}

		if ( isset ( $_POST['addcatalog'] ) ) {

			$catalog -> param = $_POST['name'];
			$catalog -> add ();
			header("location:" . BASE_URL );
			
		}


		echo "<div class='center'>";
		echo "<h5>Dashboard Penjual</h5>";
		echo "<div class='divider'>";
		echo "</div>";
		echo "</div>";




		$catalogs = $catalog -> get ();


		echo "<form method='post' action=''>";
		echo "<div class='input-field'>";
		echo "<input type='text' name='name' placeholder='Nama Produk' /> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<textarea class='materialize-textarea' placeholder='Deskripsi' name='description'></textarea> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<select name='catalog_id'>";
		echo "<option selected disabled>Pilih Katalog</option>";
		foreach ( $catalogs as $key => $value ) {
			echo "<option value='{$value['id']}'>{$value['name']}</option>";
		}
		echo "</select>";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<input placeholder='URL Gambar' type='text' name='img_url' /> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<input placeholder='Harga' type='text' name='price' /> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<input placeholder='URL Beli' type='text' name='shop_link' /> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<input type='submit' name='add' class='btn' value='Add Product' /> ";
		echo "</div>";
		echo "</form>";

		echo "<form method='post' action=''>";
		echo "<div class='input-field'>";
		echo "<input type='text' name='name' placeholder='Nama Katalog' /> ";
		echo "</div>";
		echo "<div class='input-field'>";
		echo "<input type='submit' name='addcatalog' class='btn' value='Add Katalog' /> ";
		echo "</div>";
		echo "</form>";


		
		$product -> param = "";
		$products = $product -> get ();

		if ( !empty ( $products ) ) {
			foreach ( $products as $key => $value ) {
				$no = $key + 1;
				echo "<p>{$no}. {$value['name']}</p>";
			}
		}
		
	}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" integrity="sha512-NiWqa2rceHnN3Z5j6mSAvbwwg3tiwVNxiAQaaSMSXnRRDh5C2mk/+sKQRw8qjV1vN4nf8iK2a0b048PnHbyx+Q==" crossorigin="anonymous"></script>    
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
     <script> $(document).ready(function(e) { M.AutoInit(); }) </script>     
</body>
</html>
