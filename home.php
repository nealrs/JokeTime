<?php 

	// login/session check?
	/*session_start();

	if (!isset($_SESSION['user_id'])){
 		// nobody logged in
 		//echo $_SESSION['user_id'];
 		//$_SESSION['user_id'] = 0;
 		header('Location: index.php');	
	}
	
	// set user variable for this page
	$user = $_SESSION['user_id'];
	//echo 'User id:'.$user;
*/
// load db parameters, open PDO session, set table name
	require 'dbPDO.php';
	require 'sess.php';

	// set user from session
	//$user = $_SESSION['userid'];
	//echo 'User id:'.$user;
	
	// in the absence of a login system, hack this to just be neal(1) or alek (2)
	//$user = 1;
	
	// Pull basic user info (name/avatar)
	$db_table = "users";
	$load_data = $db->prepare("SELECT * FROM $db_table WHERE user_id = '$user' AND isactive != '0'");
	$load_data->execute();
	
	// if there is no user data, this should really throw an error, but whatevs for now.
	$user_name = "Anonymous";
	$user_avatar = "blank_avatar.jpg";
	
	while ($user_info = $load_data->fetch(PDO::FETCH_ASSOC)){
		$user_name = $user_info['user_first'];
		$user_avatar = $user_info['user_avatar'];
	}	
		
include 'head.php';
echo'
	<div id = "container"> 	
		<div id = "main_content">';
			include 'secure_page_head.php';
			echo'<div class ="list_head">
			<div style="float:left;">Your Sets </div>
				<div style="float:right;margin-right:8px;">
					<a href="#"><img src="res/add.png" style="width:32px;height:32px;vertical-align:bottom;" alt="Add new set" title="Add new set" onclick=form_slide("new_set_form")></a>&nbsp;&nbsp;					
				</div>
			</div><div style="clear:both;"/></div>';
			
			// Your Sets
			echo'<div id = "listofsets">
				<div class = "listofsets_row">
					<div class="listofsets_header listsets_col1">Set Name</div>
					<div class="listofsets_header listsets_col3">Top Tags</div>
					<div class="listofsets_header listsets_col2">Recs</div>
					<div class="listofsets_header listsets_col4">Edit</div>
					<div class="listofsets_header listsets_col4">Rec</div>
				</div><div style="clear:both;"/></div>			
			';
			
			// form for new set
			echo'
			<div class="listofsets_row slider altcolor pxh" id ="new_set_form">
			<form action="new_set.php" method="post">
				<div>
					<div class="listofsets_header listsets_col8"><input type="text" name="set_title" placeholder="Set Title" size="60"></div>					
					<input type="hidden" name="user_id" value = '.$user.'>
					&nbsp;<input type="image" src="res/save.png" alt="submit" style="width:24px;height:24px;vertical-align:middle;"/>
				</div>				
			</form>
			</div>';
			
		// BEGIN LOOPED MYSQL PDO CODE
		$db_table = "sets";
		$load_data = $db->prepare("SELECT * FROM $db_table WHERE set_owner = '$user' AND isactive != '0'");
		$load_data->execute();

		$alt=0;
		while($row = $load_data->fetch(PDO::FETCH_ASSOC)){
			if ($alt%2 == 0){
			$row_type = "row_alt";
			} else {$row_type = "";}
				
			// query to count # of recordings
			$db_table = "set_rec";
			$setid = $row['set_id'];
			$rec_query= $db->prepare("SELECT COUNT(rec_set) FROM $db_table WHERE rec_set = $setid AND isactive !='0'");
			$rec_query->execute();
			$rec_count = $rec_query->fetchColumn();
			
			// query to pull tags -- save for later.	
		
			// output
			echo'
			<div id ="set_'.$setid.'" class = "listofsets_row '.$row_type.'">
				<div class="listofsets_header listsets_col1">'.$row['set_title'].'</div>
				<div class="listofsets_header listsets_col3">sobriety, movies, children, buffets</div>
				<div class="listofsets_header listsets_col2">'.$rec_count['count'].'</div>
				<div class="listofsets_header listsets_col4"><a href="set.php?setid='.$setid.'"><img src="res/edit.png" alt="Edit / Manage set" title="Edit / Manage set"></a></div>
				<div class="listofsets_header listsets_col4"><a href="new_rec.php?setid='.$setid.'"><img src="res/record.png" alt="Record set" title="Record set"></a></div>
			</div><div style="clear:both;"/></div>					
			';
			
			$alt++;	
		}
		echo'</div>';
	
		// Recent Recordings
		echo'<div class = "list_head">Recent Recordings</div>
		<div id = "listofsets">
				<div class = "listofsets_row">
					<div class="listofsets_header listsets_col1">Performance</div>
					<div class="listofsets_header listsets_col5">Date</div>
					<div class="listofsets_header listsets_col6">Notes</div>
				<!--<div class="listofsets_header listsets_col4">Del</div>-->
					<div class="listofsets_header listsets_col4">&nbsp;</div>
					<div class="listofsets_header listsets_col4">View</div>
				</div><div style="clear:both;"/></div>			
			';
		// BEGIN LOOPED MYSQL PDO CODE
		$db_table = "set_rec";
		$db_table2 = "sets";
		$load_data = $db->prepare("SELECT * FROM $db_table LEFT JOIN $db_table2 ON $db_table.rec_set = $db_table2.set_id WHERE set_owner = '$user' AND $db_table.isactive != '0' AND $db_table2.isactive != '0' ORDER BY rec_date DESC");
		$load_data->execute();

		$alt=0;
		while($row = $load_data->fetch(PDO::FETCH_ASSOC)){
			if ($alt%2 == 0){
			$row_type = "row_alt";
			} else {$row_type = "";}
				if ($row['rec_notes'] == null){
					$row['rec_notes'] = '&nbsp;';
				}
			$rec_id = $row['rec_id'];
			$set_id = $row['set_id'];	
			// output
			echo'
			<div id ="rec_'.$rec_id.'" class = "listofsets_row '.$row_type.'">
				<div class="listofsets_header listsets_col1">'.$row['set_title'].'</div>
				<div class="listofsets_header listsets_col5">'.date("m.d.y",strtotime($row['rec_date'])).'</div>
				<div class="listofsets_header listsets_col6">'.$row['rec_notes'].'</div>
			<!--<div class="listofsets_header listsets_col4"><a href="#"><img src="res/delete.png" alt="Delete Recording" title="Delete Recording"></a></div>-->
				<div class="listofsets_header listsets_col4">&nbsp;</div>
				<div class="listofsets_header listsets_col4"><a href="view_rec.php?rec_id='.$rec_id.'"><img src="res/play.png" alt="Analyze Performance" title="Analyze Performance"></a></div>
			</div><div style="clear:both;"/></div>					
			';
			
			$alt++;	
		}
		echo'</div>';
		
		echo'<div class = "list_head">Analysis</div>
		<div class = "secondary_head">This feature will be available once you record a set at least 10 times <br/>[Create 2 columns, top jokes, top tags]</div>

		</div>
		';
	
	
	// close mysql connection
	$db = null;
	include 'footer.php';
?>