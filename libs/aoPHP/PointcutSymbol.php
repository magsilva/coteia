<?php

/**
 * (c) 2004-2005 John W. Stamey, Bryan T. Saunders, and Matthew Cameron.
 * This program is licensed under the GNU General Public License.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/**
 * Java => PHP port of AOPHP v2.1
 *
 * @author: Mike Reinstein <covert_access@yahoo.com>
 * date: 03-07-2005
 */

class PointcutSymbol {
	/**
	 * String
	 * @access private
	 */
	var $name;
	
	/**
	 * String
	 * @access private
	 */
	var $sigs;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $sigs
	 */
	function PointcutSymbol($name, $sigs) {
		$this->name = $name;
		$this->sigs = $sigs;
	}
	
	
	/**
	 *
	 * @return string
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 *
	 * @param string $name
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 *
	 * @return string
	 */
	function getSigs() {
		return $this->sigs;
	}
	
	/**
	 *
	 * @param string $sigs
	 */
	function setSigs($sigs) {
		$this->sigs = $sigs;
	}
}

?>
