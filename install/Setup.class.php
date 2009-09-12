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


require_once(dirname(__FILE__) . '/ModuleSetup.class.php');

class SetupJob
{
	public $name;
	public $checkTasks;
	public $doTasks;
	
	public function run()
	{
		arsort($this->checkTasks);
		foreach ($this->checkTasks as $task) {
			$method = $task->method;
			$method->invoke($task->object);
		}
		
		arsort($this->doTasks);
		foreach ($this->checkTasks as $task) {
			$method = $task->method;
			$method->invoke($task->object);
		}
		
	}	
}

class SetupTask
{
	public $name;
	public $method;
	public $object;
}

class Setup
{
	const CLASSFILE_SUFFIX = '.setup.php';
	
	const SETUP_METHOD_PATTERN = '/setup(\d+)(.*)/';

	const CHECK_METHOD_PATTERN = '/check(.*)/';
	
	private $setupJobs;
	
	function __construct()
	{
	}

	function updateList()
	{
		$this->setupJobs = array();
		$setupFiles =  glob('*' . Setup::CLASSFILE_SUFFIX);
		foreach ($setupFiles as $setupFile) {
			// Load class
			include($setupFile);
			$className = Setup::extractName($setupFile);
			$class = new ReflectionClass($className);

			$object = new $className();
			$setupJob = new SetupJob();
			$this->setupJobs[intval($object->getPriority())] = $setupJob;
			$setupJob->name = $object->getName();
			$setupJob->checkTasks = array();
			$setupJob->doTasks = array();
			
			$methods = $class->getMethods();
			foreach ($methods as $method) {
				$methodName = $method->getName();
				if (preg_match(Setup::SETUP_METHOD_PATTERN, $methodName, $matches) != FALSE) {
					$task = new SetupTask();
					$task->name = $matches[2];
					$task->method = $method;
					$task->object = $object;
					$setupJob->doTasks[intval($matches[1])] = $task;
				}
				
				if (preg_match(Setup::CHECK_METHOD_PATTERN, $methodName, $matches) != FALSE) {
					$task = new SetupTask();
					$task->name = $matches[1];
					$task->method = $method;
					$task->object = $object;
					$setupJob->checkTasks[] = $task;
				}
				
			}
		}
	}
	
	/**
	 * Get the action name from the class's filename.
	 */
	public static function extractName($classFilename)
	{
		$name = basename($classFilename);
		$name = substr($name, 0, strlen($name) - strlen(Setup::CLASSFILE_SUFFIX));
		return $name;
	}	
	
	function run()
	{
		arsort($this->setupJobs);
		foreach ($this->setupJobs as $job) {
			$job->run();
		}
	}
}
?>