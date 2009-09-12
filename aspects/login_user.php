<?aophp filename="log.aophp" debug="off"
/*
* Authenticate user into a swiki, redirecting to the wikipage that was
* requested.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "presentation.inc.php" );
include_once( "user.inc.php" );


/*
* Set the action.
*/
if ( isset( $_REQUEST[ "login" ] ) ) {
	$action = "process";
} else {
	$action = "read";
}


if ($action == "process") {
	if ( !isset( $_REQUEST[ "username" ] ) ) {
		show_error( _( "Parameter 'username' is missing." ) );
	}
	if ( !isset( $_REQUEST[ "password" ] ) ) {
		show_error( _( "Parameter 'password' is missing." ) );
	}
	$username = $_REQUEST[ "username" ];
	$password = $_REQUEST[ "password" ];
}

if ( isset( $_REQUEST[ "dest" ] ) ) {
	$dest = $_REQUEST[ "dest" ];
} else {
	$dest = "/";
}


/**
* Process the request
*/
if ( $action == "process" ) {
	$result = user_login( $username, $password );
	if ( $result ) {
		session_write_close();
		header( "Location: $dest" );
		exit();
	} else {
		show_error( _( "The username or password was incorrect. Please, try again." ) );
	}
}


/**
* Sending the authentication form.
*/
echo get_header( _( "User login" ) );
include( "toolbar.inc.php" );
?>
<body onload="document.forms['login'].username.focus()">

<h1><?php echo _( "Login" ); ?></h1>

<p><?php echo _( "The requested resource requires you to be authenticated with a password. Please, inform the username and password:" ); ?></p>

<form name="login" method="post" action="login_user.php">
	<br /><strong><?php echo _( "Username" ); ?></strong>
	<br /><input type="text" name="username" size="16" />

	<br /><strong><?php echo _( "Password" ); ?></strong>
	<br /><input type="password" name="password" size="16" />

	<br /><input type=submit value="Login" name="login" />
	
	<input type="hidden" name="dest" value="<?php echo $dest; ?>" />
</form>

</body>

</html>
