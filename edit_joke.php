<?php

// load db parameters, open PDO session, set table name
require 'dbPDO.php';

// establish neccesary variables

	// need to add in trigger/logic for reordering?

	$joke_id = $_POST['joke_id'];
	$set_id = $_POST['set_id'];
	
	$joke_text = $_POST['joke_text'];
	$joke_tags = $_POST['joke_tags'];
	
	$delete = $_POST['delete_joke'];
	
	$db_table = 'jokes';
	
	//if ($delete > 0){
		// prepared statements
		$update_row = $db->prepare("UPDATE $db_table SET joke_text = :joke_text, joke_tags = :joke_tags, isactive = :isactive WHERE joke_id = :joke_id");
		$update_row->execute(array(':joke_text' => $joke_text, ':joke_tags' => $joke_tags, 'isactive' => $delete,':joke_id' => $joke_id));
	//} else {
		//$del_row = $db->prepare("DELETE FROM $db_table WHERE joke_id = $joke_id");
		//$del_row->execute();
		
		//$update_row = $db->prepare("UPDATE $db_table SET joke_text = :joke_text, joke_tags = :joke_tags, isactive =: isactive WHERE joke_id = :joke_id");
		//$update_row->execute(array(':joke_text' => $joke_text, ':joke_tags' => $joke_tags, 'isactive' => $delete,':joke_id' => $joke_id));
	//}
$db = null;
header( 'Location: set.php?setid='.$set_id.'' ) ;
?>