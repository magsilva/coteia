<?php
/**
* Logout from admin's "swiki".
*
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( dirname(__FILE__) . "/../function.php.inc" );

session_name( "coteia" );
session_start();

if ( isset( $_SESSION[ "swiki_0" ] ) ) {
	unset( $_SESSION[ "swiki_0" ] );
}

header("Location: $URL_COWEB");
?>
