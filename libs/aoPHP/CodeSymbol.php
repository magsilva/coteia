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

class CodeSymbol{
	
	/**
	 * String (Full JP)
	 * @access private
	 */
	var $joinpoint;
	
	/**
	 * String (JP Signature)
	 * @access private
	 */
	var $signature;
	
	/**
	 * String (Function or Variable Name)
	 * @access private
	 */
	var $sigName;
	
	/**
	 * bool
	 * @access private
	 */
	var $type;
	
	/**
	 * Constructor
	 *
	 * @param string $joinpoint
	 * @param string $signature
	 * @param string $type
	 */
	function CodeSymbol($joinpoint, $signature, $type) {
		$this->joinpoint = $joinpoint;
		$this->signature = $signature;
		$this->type = $type;
		
		if( (strpos($joinpoint, "exec(") !== FALSE) ||  (strpos($joinpoint, "execr(") !== FALSE) ){
			// Joinpoint is for a Function
			$this->sigName = trim(substr($signature,0,strpos($signature,"(")));
		}
		else if( (strpos($joinpoint, "set(") !== FALSE) || (strpos($joinpoint, "get(") !== FALSE)){
			// Joinpoint is for a Variable
		}
	}
	
	/**
	 *
	 * @return string
	 */
	function getName() {
		return $this->sigName;
	}
	
	/**
	 *
	 * @param string $name
	 */
	function setName($name) {
		$this->sigName = $name;
	}
	
	/**
	 *
	 * @return string
	 */
	function getJoinpoint() {
		return $this->joinpoint;
	}

	/**
	 *
	 * @param string $name
	 */
	function setJoinpoint($name) {
		$this->joinpoint = $name;
	}
	
	/**
	 *
	 * @return string
	 */
	function getSignature() {
		return $this->signature;
	}

	/**
	 *
	 * @param string $signature
	 */
	function setSignature($signature) {
		$this->signature = $signature;
	}
	
	/**
	 *
	 * @return bool
	 */
	function doesReturn() {
		return $this->type;
	}

	/**
	 *
	 * @param bool $type
	 */
	function setReturn($type) {
		$this->type = $type;
	}
}

?>