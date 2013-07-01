<?php

// load db parameters, open PDO session, set table name
require 'dbPDO.php';

// establish neccesary variables

	$set_title = $_POST['set_title'];
	$set_owner = $_POST['user_id'];
	$db_table = 'sets';
	
	// establish row for new set & direct to set page so user can add a joke.
	// prepared statements
	$new_row = $db->prepare("INSERT INTO $db_table (set_owner, set_title) VALUES(:set_owner, :set_title)");
	$new_row->execute(array(':set_owner' => $set_owner, ':set_title' => $set_title));
	
	// retrieve new set id (last id)
	$setid_query = $db->prepare("SELECT (set_id) FROM $db_table ORDER BY set_id DESC LIMIT 1");
	$setid_query->execute();
	$set_id = ($setid_query->fetchColumn());
	
$db = null;
header( 'Location: set.php?setid='.$set_id.'' );
?>