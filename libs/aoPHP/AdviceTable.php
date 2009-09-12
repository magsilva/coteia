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

require_once("CodeSymbol.php");
require_once("CodeTable.php");
require_once("AdviceSymbol.php");

class AdviceTable {
	/**
	 * new LinkedList<AdviceSymbol>();
	 * @access private
	 */
	var $table;
	
	/**
	 * Constructor
	 */
	function AdviceTable(){
	  $this->table = array();
	}
	
	/**
	 *
	 * @param AdviceSymbol $newAdvice
	 */
	function addAdvice($newAdvice){
		$this->table[] = $newAdvice;
	}
	
	/**
	 *
	 * @param string $sig
	 * @param string $type
	 * @return string
	 */
	function getAdviceCode($sig, $type){
		
		$size = count($this->table);
		for($i = 0;$i < $size;$i++){
			$temp = $this->table[$i];
			if( ($temp->getSignature() == $sig) && (strtolower($temp->getAdvice()) == strtolower($type))){
				return $temp->getCode();
			}
		}
		return "// No $type Advice";
	}
	
	/**
	 *
	 * @return CodeTable
	 */
	function makeCodeTable(){
		$t = new CodeTable();
		
		for($i = 0; $i < count($this->table);$i++){
			$temp = $this->table[$i];
			
			//if the AdviceSymbol isn't already in the CodeTable add it
			if(!$t->hasSignature($temp->getSignature())){
				$t->addCode(new CodeSymbol($temp->getJoinpoint(), $temp->getSignature(), $temp->isReturning()));
			}
		}
		return $t;
	}
	
	/**
	 *
	 * @param string $sig
	 * @param string $type;
	 * @return bool
	 */
	function hasAdvice($sig, $type){
		$size = count($this->table);
		for($i = 0;$i < $size;$i++){
			$temp = $this->table[$i];
			if( ($temp->getSignature() == $sig) && (strtolower($temp->getAdvice()) == strtolower($type)) ){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 *
	 */
	function printTable(){
		$size = count($this->table);
		echo "Advice Table (size=$size):\n";
		for($i = 0;$i < $size;$i++){
			$temp = $this->table[$i];
			echo "  - ". $temp->getAdvice()." ". $temp->getJptype()."(".$temp->getSignature().") : ". $temp->getCode(). "\n";
		}
	}
}

?>