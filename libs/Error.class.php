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
 
Copyright (C) 2007 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

/**
 * User error (usually user given incorrect input parameters).
 */
define('ERROR_USER', 0);

/**
 * Internal application error. This is a nice name to _BUG_.
 */
define('ERROR_APP', 1);

/**
 * PHP error. Although they're internal PHP errors, we should,
 * whenever is possible, to circunvent them (workaround are ugly,
 * but enhancing the user experience is our ultimate goal, always!).
 */
define('ERROR_PHP', 2);


class Error
{
    static $errortype = array (
    	ERROR_USER   => 'User error',
    	ERROR_APP    => 'Internal application error',
    	ERROR_PHP    => 'PHP error'
	);
	
	private $message = 'Unknown error';  
	
	private $code = 0;
	
	private $file;
	
	private $line;
	
	private $backtrace;

	public function __construct($message, $code, $file, $line, $backtrace)
	{
		$this->message = $message;
		$this->code = $code;
		$this->file = $file;
		$this->line = $line;
		$this->backtrace = $backtrace;
		
	}

	/**
	 * Message of exception.
	 */
	public final function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Code of exception.
	 */
	public final function getCode()
	{
		return $this->code;
	}
	
	/**
	 * Source filename.
	 */
	public final function getFile()
	{
		return $this->file;
	}                  

	/**
	 * Source line.
	 */
	public final function getLine()
	{
		return $this->line;
	}
	
	public final function toString()
	{
		echo "\n" . 'Error (' . Error::$errortype[$this->code] . ') in ' . $this->file . ':' . $this->line . ' - ' . $this->message;
		
	}
}

?>