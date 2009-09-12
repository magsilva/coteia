<?php
/**
* Show the swiki's map.

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

Copyright (C)
Modified by Marco Aurelio Graciotto Silva (2004).
*/

require_once('CoTeia.class.php');
require_once('ff-mvc/Action.class.php');

class Map extends Action
{
	private $forward;
	
	function __construct()
	{
		parent::__construct(__FILE__);
		$this->forward = array();
		$this->forward['success'] = 'Map.view.tpl';
	}
	
	function execute(&$request, &$response)
	{
		$coteia = new CoTeia();
		$config = $coteia->getConfig();
		
		$response['title'] = $config->name;
		$response['encoding'] = $config->encoding;
		$response['currentSemester'] = $currentSemester;
		$response['currentYear'] = $currentYear;
		$response['currentWikis'] = $currentWikis;
		$response['otherWikis'] = $otherWikis;
		
		return $this->getForward('success');
	}
	
	function getForward($forward)
	{
		return $this->forward[$forward];
	}
	
}

?>