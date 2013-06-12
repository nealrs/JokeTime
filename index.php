<?php

// check if logged in, and if so - redirect to home.php
session_start();

if (!isset($_SESSION['user_id'])){
	// nobody logged in, stay here.
	
	//echo $_SESSION['user_id'];
	//$_SESSION['user_id'] = 0;
	//header('Location: index.php');
} else {
	// logged in, so go to home.php for dashboard content
	header('Location: home.php');
}

# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'auth.php';
include 'head.php';
echo'	
	<div id = "container"> 	
		<div id ="top spacer" style="margin-top:40px;"></div>
		<div id = "main_content">
			<div id = "l_logo"><img src="res/index_logo_3.png"></div>
	
			<div id ="l_tagline">Everyone\'s a comedian</div>
			
			<div style="clear:both;"/>
				
			<!---<div id ="l_form">
				<form style="display:inline-block;" action="?login&oidType=1" method="post">
    				<button class ="zocial google">Login with Google</button>
				</form>	
				<div style="width:20px;">&nbsp;</div>
				<form style="display:inline-block;" action="?login&oidType=2" method="post">
    				<button class ="zocial yahoo">Login with Yahoo!</button>
				</form>								
			</div>--->
				
				<div id = "major_features_sprites">
					<div class="feature f1"></div>	
					<div class="feature extra-margin f2"></div>
					<div class="feature extra-margin f3"></div>	
				</div>
				
				<div id = "major_features_test">	
					<div class="ftxt">Edit sets online</div>	
					<div class="ftxt extra-margin2">Track performance</div>
					<div class="ftxt extra-margin2">Get more laughs</div>
				</div>
				
			<br/>	
			
			<div id ="l_form">
				<form style="display:inline-block;" action="?login&oidType=1" method="post">
    				<button class ="zocial google">Login with Google</button>
				</form>	
				<form style="display:inline-block;" action="?login&oidType=2" method="post">
    				<button class ="zocial yahoo">Login with Yahoo!</button>
				</form>					
			</div>
		</div>';
		include 'footer.php';
?>