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

include_once('Config.class.php');
include_once('Session.class.php');
include_once('Resource.dao.php');
include_once('AncientWiki.class.php');
include_once('AncientWiki.dao.php');

/**
 * CoTeia's main class.
 * 
 * Any application initialization is handled by this class.
 */
class CoTeia
{
	private $config;
	
	private $session;
	
	function __construct()
	{
		$this->config = Config::instance();
		$this->session = Session::instance();
		$this->session->start();
	}

	function getConfig()
	{
		return $this->config;
	}

	function countWikis()
	{
		return ResourceDAO::countResources('AncientWiki');
	}

	function countWikipages()
	{
		return ResourceDAO::count_resources('AncientWikipage');
	}
	
	function getWikis($year = null, $semester = null, $includeInvisibles = FALSE)
	{
		$wikis = array();
		
		// First, the ancient wikis.
		$dao = new AncientWikiDAO();
		$example = new AncientWiki();
		if ($year != null && $semester != null) {
			$example->semester = $semester . '_' . $year;
		}
		if ($includeInvisibles) {
			$example->public = 'N';
		} else {
			$example->public = 'S';
		}
		$wikis += $dao->searchByExample($example);
		
		// Now, the new style wikis
		
		 
		return $wikis;
	}	
}

?>