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

Copyright (C) 2006 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

require_once('Config.class.php');
require_once('adodb/adodb.inc.php');
require_once('adodb/adodb-exceptions.inc.php');

class DBConnection
{
	private $config;
	
	private $conn;
	
	static private $instance;
	
	private function __construct()
	{
		$this->config = Config::instance();
		$this->loadDriver();
		$this->connect();
		$this->conn->autoCommit = FALSE;
		$this->conn->autoRollback = TRUE;
		$this->conn->SetFetchMode(ADODB_FETCH_ASSOC);
		
	}
	
	private function loadDriver()
	{
		$this->conn = ADONewConnection($this->config->dbdriver);
	}
	
	private function connect()
	{
		try {
			$this->conn->PConnect($this->config->dbhost, $this->config->dbuser, $this->config->dbpass, $this->config->dbname);
		} catch (Exception $e1) {
			try {
				$this->conn->Connect($this->config->dbhost, $this->config->dbuser, $this->config->dbpass, $this->config->dbname);
			} catch (Exception $e) {
				throw new Exception("Could not connect to the database");
				// adodb_backtrace($e->gettrace());
			}
		}
	}
	
	public function isReady()
	{
		if ( $this->conn == null ) {
			connect();
		}
		return $this->conn->IsConnected();
	}
	
	/**
	 * The singleton method.
	 */
	public static function instance() 
	{
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance->conn;
	}
    
	/**
	 * Prevent users to clone the instance.
	 */
	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
}

?>