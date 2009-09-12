<?php
/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU 	General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copyright (C) 2006 Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

require_once('db.inc.php');
require_once('config.php');
require_once('coteia.inc.php');

interface User
{
	public $vcard;
	
	function login();
	
	function logout();
	
	function get_name();
	
	function get_email();
}


abstract class BaseUser
{
	function __construct()
	{
		login();
	}

	function __destruct()
	{
	}

	/**
	 * Start a an user session at CoTeia.
	 */
 	function login()
	{
		if (session_id() == "") {
			//	session_name( "coteia" );
			session_start();
		}
	}
	
	/**
	 * Logout from CoTeia.
	 */
	function logout()
	 {
	 	$_SESSION = array();
		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if ( isset( $_COOKIE[ session_name() ] ) ) {
			setcookie( session_name(), '', time() - 42000, '/' );
		}

		@session_destroy();
	 }


	function is_logged_in()
	{
		// If no vCard is registered in the session, than a user is not logged in.
		if ( isset($_SESSION['vcard']) ) {
			return TRUE;
		}

		return FALSE;
	}

	
	function get_name()
	{
	}
	
	function get_email()
	{
	}

} 

class AnonymousUser extends BaseUser
{
	function __construct()
	{
		parent::__construct();
		
		$name = "anonymous";
		$email = "anonymous@coteia.incubadora.fapesp.br";
	}
	
	
	function login( $password )
	{
		$vcard = <<<EOT
BEGIN:VCARD
VERSION: 2.1
FN: {$name}
EMAIL;INTERNET: {$email}
NICKNAME: {$name}
UID: {$name}
END:VCARD
EOT;
		$_SESSION['vcard'] = $vcard;
		session_write_close();

		return TRUE;
	}

}

class RegisteredUser
{
	function is_logged_in()
	{
		coteia_login();

		// If no vCard is registered in the session, than a user is not logged in.
		if ( isset($_SESSION['vcard']) ) {
			return TRUE;
		}

		return FALSE;
	}


	function login( $username, $password )
	{
		// Conectar ao banco de dados
		$dbh = db_connect();

		// Recuperar registro do usuario
		$username_db = mysql_escape_string($username);
		$sql = "SELECT id,login,nome,email FROM admin WHERE login='$username_db' AND pass=MD5('$password')";
		$result = mysql_query($sql);

		if (mysql_num_rows($result) == 0) {
			return FALSE;
		}

		$tuple = mysql_fetch_array($result);

		$vcard = <<<EOT
BEGIN:VCARD
VERSION: 2.1
FN: {$tuple['nome']}
EMAIL;INTERNET: {$tuple['email']}
NICKNAME: {$tuple['login']}
UID: {$tuple['id']}
END:VCARD
EOT;

		coteia_login();
		$_SESSION['vcard'] = $vcard;
		session_write_close();

		return TRUE;
	}

	function get_name()
	{
	}
	
	function get_email()
	{
	}
}


?>
