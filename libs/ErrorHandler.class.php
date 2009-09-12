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

require_once('Error.class.php');

class ErrorHandler
{
	function __construct()
	{
		set_error_handler(array($this, 'handleError'));
		set_exception_handler(array($this, 'handleException'));
	}
	
	function __destruct()
	{
		restore_exception_handler();
		restore_error_handler();
	}
	
	private function mapPHPError($phpErrorCode)
	{
		// define an assoc array of error string
	    // in reality the only entries we should
	    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
	    // E_USER_WARNING and E_USER_NOTICE
	    static $errortype = array (
                E_ERROR           => 'Error',
                E_WARNING         => 'Warning',
                E_PARSE           => 'Parsing Error',
                E_NOTICE          => 'Notice',
                E_CORE_ERROR      => 'Core Error',
                E_CORE_WARNING    => 'Core Warning',
                E_COMPILE_ERROR   => 'Compile Error',
                E_COMPILE_WARNING => 'Compile Warning',
                E_USER_ERROR      => 'User Error',
                E_USER_WARNING    => 'User Warning',
                E_USER_NOTICE     => 'User Notice',
                E_STRICT          => 'Runtime Notice'
        );      
		
		
		switch ($phpErrorCode) {
			case E_ERROR:
			case E_WARNING:
			case E_PARSE:
			case E_NOTICE:
			case E_STRICT:
				return ERROR_APP;
				break;
			case E_CORE_ERROR:
			case E_CORE_WARNING:
			case E_COMPILE_ERROR:
			case E_COMPILE_WARNING:
				return ERROR_PHP;
				break;
			case E_USER_ERROR:
			case E_USER_WARNING:
			case E_USER_NOTICE:
				return ERROR_USER;
				break;
			default:
				return ERROR_APP;
		}
	}

	private function mapPHPException($exception)
	{
		return $this->mapPHPError($exception->getCode());
	}

	private function backtrace($bt)
	{
		// function  string   The current function name.
		// line      integer  The current line number. 
		// file      string   The current file name.
		// class     string   The current class name.
		// object    object   The current object. 
		// type      string   The current call type. If a method call, -> is returned.
		//                    If a static method call, :: is returned. If a function call,
		//                    nothing is returned. 
		// args      array    If inside a function, this lists the functions arguments.
		//                    If inside an included file, this lists the included file name(s).
		foreach ($bt as $trace) {
			$args = '';
			foreach ($trace['args'] as $a) {
				if (! empty($args)) {
					$args .= ', ';
				}

		    	// It should be noted that if an internal php function such as
		    	// call_user_func in the backtrace, the 'file' and 'line'
		    	// entries will not be set.
				switch (gettype($a)) {
					case 'integer':
					case 'double':
						$args .= $a;
						break;
					case 'string':
						$a = substr($a, 0, 64) . ((strlen($a) > 64) ? '...' : '');
						$args .= "\"$a\"";
						break;
					case 'array':
						$args .= 'Array('.count($a).')';
						break;
					case 'object':
						$args .= 'Object('.get_class($a).')';
						break;
					case 'resource':
						$args .= 'Resource('.strstr($a, '#').')';
						break;
					case 'boolean':
						$args .= $a ? 'True' : 'False';
						break;
					case 'NULL':
						$args .= 'Null';
						break;
					default:
						$args .= 'Unknown';
				}
			}
			
			if (! isset($trace['file'])) {
				echo('[PHP core called function]');
			} else {
				echo('File: ' . $trace['file']);
			}

			$output .= "\n";
			$output .= 'file: ' . $trace['file'] . ':' . $trace['line'] . "\n";
			$output .= 'call: ' . $trace['class']  . $trace['type'] . $trace['function'] . $args . "\n";
			$output .= 'mem: ' . ceil(memory_get_usage() / 1024) . ' KiB' . "\n";	
		}
		
	}

	private function __handleError($error)
	{
		echo $error->toString();
		
		switch ($error->getCode()) {
			case ERROR_USER:
				break;
				
			case ERROR_APP:
				break;
				
			case ERROR_PHP;
				break;
		}
		
		/* Don't execute PHP internal error handler */
		return true;
	}



	public function handleError($errno, $errstr, $errfile, $errline)
	{
		$bt = debug_backtrace();
		
		$error = new Error($errstr, $this->mapPHPError($errno), $errfile, $errline, $bt);
    	return $this->__handleError($error);
    	
    	// $this->template_engine->addError($errstr);
	}
	
	
	public function handleException($exception)
	{
		$bt = $exception->getTrace();
				
		// $bt = debug_backtrace();
		// http://br.php.net/manual/en/function.debug-backtrace.php#54993
		// Get class, function called by caller of caller of caller
		// $class = $bt[2]['class'];
		// $function = $bt[2]['function'];
		$class = '';
		if (array_key_exists('class', $bt)) {
			$class = $bt[0]['class'];
		}
		
		$function = '';
		if (array_key_exists('function', $bt)) {
			$function = $bt[0]['function'];
		}
		
		// get file, line where call to caller of caller was made
		$file = $bt[0]['file'];
		// $file = $exception->getFile();
		
		$line = $bt[0]['line'];
		// $line = $exception->getLine();

		// $bt = array_slice($bt, 2);

		$error = new Error($exception->getMessage(), $this->mapPHPException($exception), $file, $line, $bt);
		return $this->__handleError($error);
	}
}

?>