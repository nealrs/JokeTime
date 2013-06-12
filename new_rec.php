<?php 

// load db parameters, open PDO session, set table name
require 'dbPDO.php';
require 'sess.php';

// this page allows user to create a new set.


// Pull basic user info (name/avatar)
	$db_table = "users";
	$db_table2 = "sets";

if (isset($_GET['setid']) && $_GET['setid'] > 0) {
		$set_id = $_GET['setid'];
		
		$load_data = $db->prepare("SELECT set_title FROM $db_table LEFT JOIN $db_table2 ON $db_table.user_id = $db_table2.set_owner WHERE set_id = $set_id AND $db_table.isactive != '0'");
		$load_data->execute();
		$set_name = $load_data->fetchColumn();
		
		
	} else {
		// if there is no user data, this should really throw an error, but whatevs for now.
		//$user_name = "Anonymous";
		//$user_avatar = "blank_avatar.jpg";
		
		// in the absence of a login system, hack this to just be neal(1) or alek (2)
		//$user = 2;
		//$user_name = "Rando";
		//$user_avatar = "blank_avatar.png";
		//$set_name = "New Set";
	}
include 'head.php';
echo'
	<div id = "container"> 	
		<div id = "main_content">
			<div id = "main_content">';
			include 'secure_page_head.php';
			echo'
			<div class ="list_head">
				<div style="float:left;">'.$set_name.'</div>
				
				<div class="slider leftslide" style="float:left;" id ="title_form">
				<form action="edit_set_title.php" method="post">
					<div>
						<input type="text" name="set_title" value="'.$set_name.'" placeholder="enter set name"> 
						<span style="font-size:.6em;">delete set?&nbsp;</span>
						<select name="is_active">
							<option value = 1 selected>No</option>
							<option value = 0>Yes</option>
						</select> 
						 
						<input type="hidden" name="set_id" value = '.$set_id.'>
						<input type="image" src="res/save.png" alt="submit" style="width:24px;height:24px;vertical-align:middle;"/>
					</div>				
				</form>
				</div>
				
				<div style="float:right;margin-right:5px;">
					<!--<a href="#"><img src="res/delete2.png" style="width:32px;height:32px;vertical-align:middle;" alt="Delete set" title="Delete set"></a>&nbsp;&nbsp;-->
					<a href="#"><img src="res/edit2.png" style="width:32px;height:32px;vertical-align:middle;" alt="Edit set" title="Edit set" onclick=form_slide("title_form")></a>&nbsp;&nbsp;
					<!--<a href="#"><img src="res/add.png" style="width:32px;height:32px;vertical-align:middle;" alt="Add joke" title="Add joke" onclick=form_slide("new_joke_form")></a>&nbsp;&nbsp;-->
				</div>
			</div><div style="clear:both;"/></div>
			
			
			
			<div id = "listofsets">
				<div class = "listofsets_row">
					<div class="listofsets_header listsets_col4">Order</div>
					<div class="listofsets_header listsets_col1">Joke Prompt</div>
					<div class="listofsets_header listsets_col7">Tags</div>
					<div class="listofsets_header listsets_col4"><img src="res/good.png" style="width:24px;height:24px;vertical-align:middle;" alt="Number of times joke went well" title="Number of times joke went well"></div>
					<div class="listofsets_header listsets_col4"><img src="res/bad.png" style="width:24px;height:24px;vertical-align:middle;"alt="Number of times joke did not go well" title="Number of times joke did not work well"></div>
					<!--<div class="listofsets_header listsets_col4">Edit</div>-->
				</div><div style="clear:both;"/></div>			
			';
			
			echo'
			<div class="listofsets_row slider altcolor pxh" id ="new_joke_form">
			<form action="new_joke.php" method="post">
				<div>
					<div class="listofsets_header listsets_col8"><input type="text" name="joke_text" placeholder="add a new joke/prompt here" size="57"></div>
					<div class="listofsets_header listsets_col8"><input type="text" name="joke_tags" placeholder="comma, separated, descriptive, tags" size="57"></div>
					
					<input type="hidden" name="set_id" value = '.$set_id.'>
					&nbsp;<input type="image" src="res/save.png" alt="submit" style="width:24px;height:24px;vertical-align:middle;"/>
				</div>				
			</form>
			</div>';
			
			
		// BEGIN LOOPED MYSQL PDO CODE
		$db_table = "sets";
		$db_table2 = "jokes";
		
		$load_data = $db->prepare("SELECT * FROM $db_table LEFT JOIN $db_table2 ON $db_table.set_id = $db_table2.joke_set_id WHERE set_id = $set_id AND $db_table2.isactive = 1 ORDER BY $db_table2.joke_order ASC");
		$load_data->execute();

		$alt=0;
		
		// if no jokes - execute javascript to pop open new joke line
		//if (!($load_data->fetch(PDO::FETCH_ASSOC))){	
			
			// actually, we'll just always keep the panel open.
			//echo '<script type=\'text/javascript\'>form_slide("new_joke_form")</script>';
		//}
		
		while($row = $load_data->fetch(PDO::FETCH_ASSOC)){
			if ($alt%2 == 0){
			$row_type = "row_alt";
			} else {$row_type = "";}
		
		// counters for joke performance (do counts for joke performance (count both 0 and 1) across all jokes for a jokeID
		
		$joke_id = $row['joke_id'];
		
		$db_table = "jokes";
		$db_table2 = "joke_rec";
		
		$up_query = $db->prepare("SELECT COUNT(rec_reception) FROM $db_table LEFT JOIN $db_table2 ON $db_table.joke_id = $db_table2.rec_joke_id WHERE joke_id = $joke_id AND rec_reception > 0");
		$up_query->execute();
		
		$dwn_query = $db->prepare("SELECT COUNT(rec_reception) FROM $db_table LEFT JOIN $db_table2 ON $db_table.joke_id = $db_table2.rec_joke_id WHERE joke_id = $joke_id AND rec_reception < 0");
		$dwn_query->execute();
		
		$upcount = $up_query->fetchColumn();
		$dwncount = $dwn_query->fetchColumn();
		
		if ($upcount < 1){$upcount = '-';}
		if ($dwncount < 1){$dwncount = '-';}
		
		if ($row['joke_text'] == null){
			$joke = '&nbsp;';
		} else {$joke = $row['joke_text'];}
		
		if ($row['joke_tags'] == null){
			$tags = '&nbsp;';
		} else {$tags = $row['joke_tags'];}
		
		// new row form
		echo'
			<div id ="joke_'.$joke_id.'" class = "listofsets_row '.$row_type.'">
				<div class="listofsets_header listsets_col4">'.($alt+1).'</div>
				<div class="listofsets_header listsets_col1">'.$joke.'</div>
				<div class="listofsets_header listsets_col7">'.$tags.'</div>
				<div class="listofsets_header listsets_col4"><a href="#">'.$upcount.'</div>
				<div class="listofsets_header listsets_col4"><a href="#">'.$dwncount.'</div>
				<!--<div class="listofsets_header listsets_col4"><a href="#"><img src="res/edit.png" alt="Edit Joke" title="Edit Joke" style="width:24px;height:24px;vertical-align:bottom;" onclick=form_slide("edit_'.$joke_id.'")></a></div>--->

			</div><div style="clear:both;"/></div>					
		';
		
		// edit joke form
		echo'
			<div class="listofsets_row slider altcolor pxh" id ="edit_'.$joke_id.'">
			<form action="edit_joke.php" method="post">
				<div>
					<div class="listofsets_header listsets_col8"><input type="text" name="joke_text" placeholder="add a new joke/prompt here" size="45" value="'.$joke.'"></div>
					<div class="listofsets_header listsets_col8"><input type="text" name="joke_tags" placeholder="comma, separated, descriptive, tags" size="46" value="'.$tags.'"></div>
					
					<span style="font-size:1em;">delete?</span>
					<select name="delete_joke">
						<option value = 1 selected>No</option>
						<option value = 0>Yes</option>
					</select> 
					
					<input type="hidden" name="set_id" value = '.$set_id.'>
					<input type="hidden" name="joke_id" value = '.$joke_id.'>
					&nbsp;<input type="image" src="res/save.png" alt="submit" style="width:24px;height:24px;vertical-align:middle;"/>
				</div>				
			</form>
			</div>';
			
		$alt++;	
		}
		echo'</div>';
	
	// close mysql connection
	$db = null;
	include 'footer.php';
?>