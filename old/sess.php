<?php
	// login/session check?
	session_start();

	if (!isset($_SESSION['user_id'])){
 		header('Location: index.php');	
	}
	
	// set user variable for this page
	$user = $_SESSION['user_id'];
?>	