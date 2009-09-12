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

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copyright (C) 2006 Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

/**
 * When using CoTeia, a 'Session' is started to handle user information.
 * CoTeia should not rely upon the fact it's running within a Web service. It
 * would be nice if it could be called from the command line (to run some batch
 * functions, like those run by ISE.
 */
class Session
{

	private $auth_tokens;
	
	private $users;
	
			
	/**
	 * Single instance of the class.
	 */
    private static $instance;

	/**
	 * Constructor (it is private because this class implements the Singleton
	 * pattern.
	 */
	protected function __construct()
	{
		$this->auth_tokens = array();
		$this->users = array();
	}
	
	/**
	 * Discover the kind of session that must be build.
	 */
	private static function guess_type()
	{
		$php_api = php_sapi_name();
		switch ($php_api) {
			case 'cli':
				$type = 'Cmd';
				break;

			case 'activescript':
			case 'aolserver':
			case 'apache':
			case 'apache2filter':
			case 'apache2handler':
			case 'caudium':
			case 'cgi':
			case 'cgi-fcgi':
			case 'continuity':
			case 'embed':
			case 'isapi':
			case 'java_servlet':
			case 'milter':
			case 'nsapi':
			case 'phttpd':
			case 'pi3web':
			case 'roxen':
			case 'thttpd':
			case 'tux':
			case 'webjames':
				$type = 'HTTP';
				break;
			default:
				$type = 'Cmd';
		}
		return $type;
	}
	
	/**
	 * Discover the kind of session that must be build.
	 */
    private static function factory($type)
    {
        if (include_once($type . '.class.php')) {
            $classname = $type;
            return new $classname;
        } else {
            throw new Exception('Resource not found');
        }
    }
	

	/**
	 * Implementation of the singleton pattern. It will create a 'Session'.
	 * Actually, it will be an HTTPSession or CmdSession, this will be
	 * discovered at runtime, automatically.
	 */
	public static function instance() 
	{
		if ( ! isset( self::$instance ) ) {
			$c = self::guess_type() . __CLASS__; 
			self::$instance =& self::factory($c);
		}
		return self::$instance;
	}
    
	/**
	 * Prevent users to clone the instance.
	 */
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	public function start()
	{
	}
	
	public function stop()
	{
	}
	
	public function get_auth_token($resource)
	{
		if (isset($this->auth_tokens[$resource])) {
			return $this->auth_tokens[$resource];
		}
		return FALSE;
	}

	public function set_auth_token($resource, $value = TRUE)
	{
		$this->auth_tokes[$resource] = $value;
	}

	/**
	 * Add an user to the session. Usually, just one user join a session, but
	 * nothing disallows more than one user to join a session.
	 */
	public function join($user)
	{
		$this->users[] = $user;
	}
	
	/**
	 * Remove an user from the session.
	 */
	public function leave($user)
	{
		for ($i = len($this->user); $i >= 0; $i--) {
			if ($this->user[$i] == $user) {
				unset($this->user[$i]);
			}
		}
	}
}
?>