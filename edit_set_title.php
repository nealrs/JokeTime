<?php

// load db parameters, open PDO session, set table name
require 'dbPDO.php';

// establish neccesary variables

	$set_id = $_POST['set_id'];
	$set_title = $_POST['set_title'];
	$isactive = $_POST['is_active'];
	
	//echo $isactive;
	
	$db_table = 'sets';
	
	// prepared statements
	$update_row = $db->prepare("UPDATE $db_table SET set_title = :set_title, isactive = :isactive WHERE set_id = :set_id");
	$update_row->execute(array(':set_title' => $set_title, ':isactive' => $isactive, ':set_id' => $set_id));

$db = null;

// if we are deleting the set, return to home.php
if ($isactive < 1) {Pac
	header('Location: home.php');
} else {
	header('Location: set.php?setid='.$set_id.'');
  }
//header('Location: set.php?setid='.$set_id.'') ;
?>