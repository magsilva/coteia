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

class Mapping
{
	private $path;
	
	private $action;
	
	private $forwards;
	
	function __construct($path, $action, $forwards)
	{
		$this->path = $path;
		$this->action = $action;
		$this->forwards = $forwards;
	}
	
	function matches($path)
	{
		if ($this->path == $path) {
			return true;
		}
	}
	
	function getAction()
	{
		return $this->action;
	}
	
	function getForwards()
	{
		return $this->forwards;
	}
}

?>