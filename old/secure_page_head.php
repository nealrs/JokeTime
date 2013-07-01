<?php
echo'<div id = "home_head">
	<img src='.$_SESSION['user_avatar'].' alt = '.$_SESSION['user_first'].' title = '.$_SESSION['user_first'].' style="width:72px;height:72px;vertical-align:bottom;">&nbsp;<span class="head_name">'.$_SESSION['user_first'].'</span>

	<div style="float:right; margin-left:10px;">		
		<form action="logout.php" method="post">
    		<button class ="zocial secondary">Logout</button>
		</form>	
	</div>
	
	<div style="float:right;">		
		<form action="home.php" method="post">
    		<button class ="zocial primary">Home</button>
		</form>
		<!--<a href="home.php" class="zocial primary">Home</a>-->
	</div>
</div><div style="clear:both;"/></div>
';
?>