<?php

// load db parameters, open PDO session, set table name
require 'dbPDO.php';

// establish neccesary variables

	// need to add in trigger/logic for reordering?

	$rec_id = $_POST['rec_id'];
	$rec_title = $_POST['rec_title'];
	$rec_notes = $_POST['rec_notes'];
	$delete = $_POST['delete'];
	
	$set_id = $_POST['set_id'];
	
	$db_table = 'set_rec';
	
	// prepared statements
	if ($delete == 0){
		// set row to inactive and redirect to home.php
		//$update_row = $db->prepare("UPDATE $db_table SET rec_title = :rec_title, rec_notes = :rec_notes, isactive = :isactive WHERE rec_id = :rec_id");
		//$update_row->execute(array(':rec_title' => $rec_title, ':rec_notes' => $rec_notes, ':rec_id' => $rec_id, ':isactive' => $delete));
		
		$update_row = $db->prepare("UPDATE $db_table SET isactive = :isactive WHERE rec_id = :rec_id");
		$update_row->execute(array(':rec_id' => $rec_id, ':isactive' => $delete));
		header('Location: home.php');	
	
	} else {
		// don't do anything and just reload the page
		// need to consider adding in notes & title & date edits
		//$update_row = $db->prepare("UPDATE $db_table SET rec_title = :rec_title, rec_notes = :rec_notes, isactive = :isactive WHERE rec_id = :rec_id");
		//$update_row->execute(array(':rec_title' => $rec_title, ':rec_notes' => $rec_notes, ':rec_id' => $rec_id, ':isactive' => $delete));
		
		header('Location: view_rec.php?rec_id='.$rec_id.'');
	}
$db = null;

?>