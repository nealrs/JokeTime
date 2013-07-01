<?php 

// load db parameters, open PDO session, set table name
require 'dbPDO.php';
require 'sess.php';

// Pull basic user info (name/avatar)
	$db_table = "users"; // provides user info
	$db_table2 = "sets"; // links set id to user id
	$db_table3 = "set_rec"; // links rec id to set id to set info

if (isset($_GET['rec_id']) && $_GET['rec_id'] > 0) {
		$rec_id = $_GET['rec_id'];
		
		// convert to single join - use session variable
		
		$load_data = $db->prepare("SELECT * FROM $db_table u, $db_table2 s, $db_table3 r WHERE u.user_id = s.set_owner AND s.set_id = r.rec_set AND r.rec_id = $rec_id AND r.isactive != '0'");
		$load_data->execute();
		
		while ($user_info = $load_data->fetch(PDO::FETCH_ASSOC)){
			$set_name = $user_info['set_title'];
			$rec_title = $user_info['rec_title'];
			$set_id = $user_info['set_id'];
			$rec_date = date("m.d.y",strtotime($user_info['rec_date']));			
		}
		
	} else {
		// if there is no user data, this should really throw an error, but whatevs for now.
		//$user_name = "Anonymous";
		//$user_avatar = "blank_avatar.jpg";
		
		// in the absence of a login system, hack this to just be neal(1) or alek (2)
		//$user = 1;
		//$user_name = "Rando";
		//$user_avatar = "def_avatar.png";
		//$set_name = "New Set";
	}

include 'head.php';
echo'
	<div id = "container"> 	
		<div id = "main_content">
			<div id = "main_content">';
			
			include 'secure_page_head.php';
			
			echo'<div class ="list_head">
				<div style="float:left;">'.$set_name.' - '.$rec_date.'</div>
				
				<div class="slider leftslide" style="float:left;" id ="edit_rec">
				<form action="edit_rec.php" method="post">
					<div>
						<!--<input type="text" name="rec_title" value="'.$rec_title.'" placeholder="enter recording name">-->
						<!--<input type="text" name="rec_notes" value="'.$rec_notes.'" placeholder="enter recording notes"> -->
						<span style="font-size:.6em;">delete rec?&nbsp;</span>
						<select name="delete">
							<option value = 1 selected>No</option>
							<option value = 0>Yes</option>
						</select> 
						<input type="hidden" name="rec_id" value = '.$rec_id.'>
						<input type="hidden" name="set_id" value = '.$set_id.'>
						<input type="image" src="res/save.png" alt="submit" style="width:24px;height:24px;vertical-align:middle;"/>
					</div>				
				</form>
				</div>
				
				<div style="float:right;margin-right:5px;">
					<a href="#">&nbsp;</a>
					<a href="#"><img src="res/edit2.png" style="width:32px;height:32px;vertical-align:middle;" alt="Edit Recording" title="Edit Recording" onclick=form_slide("edit_rec")></a>
				</div>
			</div><div style="clear:both;"/></div>
			
			
			
			<div id = "listofsets">
				<div class = "listofsets_row">
					<div class="listofsets_header listsets_col4">Order</div>
					<div class="listofsets_header listsets_col1">Joke Prompt</div>
					<div class="listofsets_header listsets_col7">Tags</div>
					<div class="listofsets_header listsets_col4">&nbsp;</div>
					<div class="listofsets_header listsets_col4">Y/N</div>
				</div><div style="clear:both;"/></div>			
			';
			
		// BEGIN LOOPED MYSQL PDO CODE
		$db_table = "joke_rec";
		$db_table2 = "jokes";
		
		$load_data = $db->prepare("SELECT * FROM $db_table LEFT JOIN $db_table2 ON $db_table.rec_joke_id = $db_table2.joke_id WHERE $db_table.rec_id = '$rec_id' ORDER BY $db_table.rec_order ASC");
		$load_data->execute();

		$alt=0;
		while($row = $load_data->fetch(PDO::FETCH_ASSOC)){
			if ($alt%2 == 0){
			$row_type = "row_alt";
			} else {$row_type = "";}
				
		if ($row['joke_text'] == null){
			$joke = '&nbsp;';
		} else {$joke = $row['joke_text'];}
		
		if ($row['joke_tags'] == null){
			$tags = '&nbsp;';
		} else {$tags = $row['joke_tags'];}
		
		if ($row['rec_reception'] > 0) {$response = "good";} elseif ($row['rec_reception'] <= 0) {$response = "bad";}
		
		// new row form
		echo'
			<div id ="joke_'.$joke_id.'" class = "listofsets_row '.$row_type.'">
				<div class="listofsets_header listsets_col4">'.($alt+1).'</div>
				<div class="listofsets_header listsets_col1">'.$joke.'</div>
				<div class="listofsets_header listsets_col7">'.$tags.'</div>
				<div class="listofsets_header listsets_col4">&nbsp</div>
				<div class="listofsets_header listsets_col4"><img src="res/'.$response.'.png" alt="'.$reception.' joke" title="'.$reception.' joke" style="width:24px;height:24px;vertical-align:bottom;")></div>
			</div><div style="clear:both;"/></div>					
		';
			
		$alt++;	
		}
		echo'</div>';
	
	// close mysql connection
	$db = null;
	include 'footer.php';
?>