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

require_once('FileUtil.class.php');
require_once('Action.class.php');
require_once('Mapping.class.php');

/**
 * Create, automatically, mappings for every action found at the root
 * directory. The Action filenames are detected by the pattern '.action.php'.
 */
class AutoMapping
{
	private $rootDir;
	
	private $mapping;	
		
	/**
	 * Create a AutoMapping instance.
	 * 
	 * @param rootDir Directory to be used as root. If not specified, will use
	 * the directory this file has been saved to.
	 */
	function __construct($rootDir = null)
	{
		if ($rootDir == null) {
			$rootDir = dirname(__FILE__);
		}
		$this->setRootDir($rootDir);
		$this->refreshMapping();
	}

	/**
	 * Set the root directory.
	 */
	private function setRootDir($rootDir)
	{
		if (! FileUtil::isDir($rootDir)) {
			throw new Exception('Invalid root directory');
		}
		$this->rootDir = $rootDir;
	}

	/**
	 * Update the action's mapping.
	 */
	private function refreshMapping()
	{
		$actionPattern = $this->rootDir . '/*' . Action::CLASSFILE_SUFFIX;
		$actions = glob($actionPattern);
	
		$this->mapping = array();
		foreach ($actions as $action) {
			require_once($action);
			$action_name = Action::extractName($action);
			$action_instance = new $action_name();
			$mapping = new Mapping($action_instance->getName(), $action_instance, $action_instance->getViews());
			$this->mapping[] = $mapping;
		}
	}
	
	public function getMapping()
	{
		return $this->mapping;
	}	
}

?>