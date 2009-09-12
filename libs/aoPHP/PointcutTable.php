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

require_once("PointcutSymbol.php");

class PointcutTable {
	/**
	 * LinkedList<PointcutSymbol>
	 *
	 * @access private
	 */
	var $table;
	
	/**
	 * Constructor
	 */
	function PointcutTable(){
	  $this->table = array();
	}
	
	/**
	 * 
	 * @param PointcutSymbol $newPC
	 */
	function addPC($newPC){
		$this->table->add($newPC);
	}
	
	/**
	 *
	 */
	function printTable(){
		$size = count($this->table);
		echo "Pointcut Table (size=$size):\n";

		for($i = 0; $i < $size;$i++){
			$temp = $this->table[i];
			echo "  - " . $temp->getName() . " - " . $temp->getSigs() . "\n";
		}
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	function contains($name){
		$size = count($this->table);
		for($i = 0; $i < $size;$i++){
			$temp = $this->table[i];
			if(strtolower($temp->getName()) == strtolower($name)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 * @param string $name
	 * @return string
	 */	
	function getSigs($name){
		$size = count($this->table);
		for($i = 0; $i < $size;$i++){
			$temp = $this->table[i];
			if(strtolower($temp->getName()) == strtolower($name)){
				return $temp->getSigs();
			}
		}
		return "";
	}
}


?>