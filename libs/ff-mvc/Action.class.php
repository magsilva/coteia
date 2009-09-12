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
 
Copyright (C) 2007 Marcio Ghiraldelli <marcio.gh@gmail.com>
*/

require_once(dirname(__FILE__) . '/../FileUtil.class.php');

/**
 * Default action class.
 */
class Action
{
	private $name;
	
	private $classFilename;

	/**
	 * Default filename suffix for an action.
	 */
	const CLASSFILE_SUFFIX = '.action.php';

	/**
	 * Create a new action.
	 * 
	 * @param $className The filename for the class responsable for the action.
	 * @param $name The action name. This optional: it will use the name of the
	 * action's class as default.
	 */
	public function __construct($classFilename = null, $name = null)
	{
		if ($classFilename == null) {
			$classFilename = realpath(__FILE__);
		}
		
		if ($name == null) {
			$name = Action::extractName($classFilename);
		}
		$this->setName($name);
		$this->setClass($classFilename);
	}

	protected function setName($name)
	{
		$name = trim($name);
		if (strlen($name) == 0) {
			throw new Exception('Invalid action name');
		}
		
		$this->name = $name;
	}
	
	protected function setClass($classFilename)
	{
		if (! FileUtil::isFile($classFilename)) {
			throw new Exception('Invalid class file');
		}
		$this->classFilename = $classFilename;
	} 
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getClassFilename()
	{
		return $this->classFilename;
	}
	
	/**
	 * Get the action name from the class's filename.
	 */
	public static function extractName($classFilename)
	{
		$name = basename($classFilename);
		$name = substr($name, 0, strlen($name) - strlen(Action::CLASSFILE_SUFFIX));
		return $name;
	}	

	/**
	 * Views for the action.
	 * 
	 * @return Return an array in the following format: "erro" =>" erro. php",
	 * "success" =>" lista. php".
	 */
	public function getViews()
	{
	}

	/**
	 * Process a request.
	 */
	public function execute(&$request, &$response)
	{
		// return $forward;
	}

}

?>