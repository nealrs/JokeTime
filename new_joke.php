<?php

// load db parameters, open PDO session, set table name
require 'dbPDO.php';

// establish neccesary variables

	$joke_id = $_POST['joke_id'];
	$set_id = $_POST['set_id'];
	$joke_text = $_POST['joke_text'];
	$joke_tags = $_POST['joke_tags'];
	
	$db_table = 'jokes';
	
	// calculate joke order and then increment by one to put joke at end of list.
	$order_query = $db->prepare("SELECT (joke_order) FROM $db_table ORDER BY joke_order DESC LIMIT 1");
	$order_query->execute();
	$joke_order = ($order_query->fetchColumn()+1);
	
	// prepared statements
	$new_row = $db->prepare("INSERT INTO $db_table (joke_id, joke_set_id, joke_text, joke_tags, joke_order) VALUES(:joke_id, :joke_set_id, :joke_text, :joke_tags, :joke_order)");
	$new_row->execute(array(':joke_id' => $joke_id, ':joke_set_id' => $set_id, ':joke_text' => $joke_text, ':joke_tags' => $joke_tags, ':joke_order' => $joke_order));

$db = null;
header( 'Location: set.php?setid='.$set_id.'' ) ;
?>