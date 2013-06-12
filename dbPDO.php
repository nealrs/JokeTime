<?php 
// MYSQL PDO INIT
		require 'dbinfo.php';		
		//$db = new PDO('mysql:host=db.joketi.me; dbname=joketime; charset=UTF8', 'jktmadmin', 'Fuc6L7ClibXX');
		$db = new PDO('mysql:host='.$dbhost.'; dbname='.$dbname.'; charset=UTF8', $dbuser, $dbpass);
		
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
		/*$db_table="dash";*/
		
// END MYSQL INIT
?>
