<?php 

//session_start();

# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'openid.php';
require 'dbPDO.php';

try {
    # Change 'localhost' to your domain name.
    
    // what provider are we using? 1 for google, anything else for yahoo
    if(isset($_GET['oidType'])) {$oidType = $_GET['oidType'];}
    
    $openid = new LightOpenID('joketi.me');
    if(!$openid->mode) {
        if(isset($_GET['login'])) {
        	//$openid->identity = 'https://www.google.com/accounts/o8/id';
           
			// are we doing google (1) or yahoo (anything else)?
            if ($oidType==1){
        		$openid->identity = 'https://www.google.com/accounts/o8/id';
    		} else {$openid->identity = 'https://me.yahoo.com';	}
           
            $openid->required = array('namePerson/first', 'namePerson/friendly', 'contact/email');
            header('Location: ' . $openid->authUrl());
        }
        
    } elseif($openid->mode == 'cancel') {
        //echo 'User has canceled authentication!';
    }
    
    // NEW auth code
    if ($openid->validate()){
  	// valid authentication
    
    $returnVariables = $openid->getAttributes();
  	$db_table = 'users';
  	
  	//set user email & first name variables. google takes (/first), yahoo does (/friendly)
  	$email = $returnVariables['contact/email'];
  	$avatar = get_gravatar($email);
	if ($oidType==1){
        $firstname = $returnVariables['namePerson/first'];
    } else { $firstname = $returnVariables['namePerson/friendly'];}
  	
  	
	$isuser = $db->prepare("SELECT user_id FROM $db_table WHERE user_login = '$email' AND isactive != 0");
	$isuser->execute();
  
  	$user_id = $isuser->fetchColumn();
  	//echo $user_id;
  	
  	//$toast = 'user id is _'.$user_id.'_. if blank, new user';
  	//echo "<script type='text/javascript'>alert('$toast')</script>";
  
  	// add check for inactive accounts. - email support
  	
  	if (!$user_id){
  		// first time user, create new account 
		
  		$new_user = $db->prepare("INSERT INTO $db_table (user_login, user_first, user_avatar) VALUES(:user_login, :user_first, :user_avatar)");
		$new_user->execute(array(':user_login' => $email, ':user_first' => $firstname, ':user_avatar' => $avatar));  
		
  		// could also potentially do some onboarding/new user stuff here - diff redirect
  	}	
  		// OK, now go back and get that user_id we just checked for. - whether or not this is a new user
  		$getid = $db->prepare("SELECT user_id, user_first, user_avatar FROM $db_table WHERE user_login = '$email'");
		$getid->execute();
		
		while ($user_info = $getid->fetch(PDO::FETCH_ASSOC)){
			$_SESSION['user_id'] = $user_info['user_id'];
			$_SESSION['user_first'] = $user_info['user_first'];
			$_SESSION['user_avatar']  = $user_info['user_avatar'];
		}
		
		//$toast= $_SESSION['user_id'].' '.$_SESSION['user_first'].' '.$_SESSION['user_avatar'];
		//echo "<script type='text/javascript'>alert('$toast')</script>";
  		
  		header('Location: home.php');
	}
    
    // OLD / SIMPLE validation code
    /*
    if($openid->validate()) {
		$returnVariables = $openid->getAttributes();
		//echo 'User ' . $openid->identity . ' has logged in with this email address ' . $returnVariables['contact/email'];
		echo 'Hi '. $returnVariables['namePerson/first'].', welcome to JokeTime! Your email address is '. $returnVariables['contact/email'];
	} */
	
	
	else {
		//echo 'User not logged in';
	}
    
    
} catch(ErrorException $e) {
    echo $e->getMessage();
}    


/**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
function get_gravatar( $email, $s = 80, $d = 'http%3A%2F%2Fjoketi.me%2Fres%2Fdef_avatar.png', $r = 'r', $img = false, $atts = array() ) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5( strtolower( trim( $email ) ) );
	$url .= "?s=$s&d=$d&r=$r";
	if ( $img ) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}

?>