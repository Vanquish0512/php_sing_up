<?php

function db_connect(){
	$dsn = 'mysql:xxxxxxxx';
	$user = 'xxxxxxxx';
	$password = 'xxxxxxxx';
	
	try{
		$pdo = new PDO($dsn, $user, $password);
		return $pdo;
	}catch (PDOException $e){
	    	print('Error:'.$e->getMessage());
	    	die();
	}
}
 //$pdo = db_connect();
?>