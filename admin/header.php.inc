<?php
/**
* Check if the user has been authenticated as admin.
*
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( dirname(__FILE__) . "/../function.php.inc" );

if ( isset( $_REQUEST[ "login" ] ) ) {
	$username = $_REQUEST[ "username" ];
	$password = $_REQUEST[ "password" ];

	coteia_login_swiki( $username, $password, 0 );
} else {
  session_name( "coteia" );
  session_start();
}

if ( !isset( $_SESSION[ "swiki_0" ] ) || $_SESSION[ "swiki_0" ] !== true ) {
	header( "Location: login.php" );
	exit();
}

?>
