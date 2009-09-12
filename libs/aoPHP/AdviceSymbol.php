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


class AdviceSymbol{
	/**
	 * String (Type of advice)
	 * @access private
	 */
	var $advice;
	
	/**
	 * String (Type of Joinpoint)
	 * @access private
	 */
	var $jptype;
	
	/**
	 * String (Full Joinpoint)
	 * @access private
	 */
	var $joinpoint;
	
	/**
	 * String (JP Signature)
	 * @access private
	 */
	var $signature; 
	
	/**
	 * bool (Does return)
	 * @access private
	 */
	var $isReturning;
	
	/**
	 * String (Advice Code)
	 * @access private
	 */
	var $code;
	
	
	/**
	 * Constructor
	 *
	 * @param string $signature
	 * @param string $adviceType
	 * @param string $jpType
	 * @param string $code
	 */
	function AdviceSymbol($signature, $adviceType, $jpType, $code) {
		$this->signature = $signature;
		$this->advice = $adviceType;
		$this->jptype = $jpType;
		$this->code = $code;
		$this->isReturning = ($jpType == "execr");
		$this->joinpoint = $jpType. "($signature)";
	}
	
	/**
	 *
	 * @return string
	 */
	function getAdvice() {
		return $this->advice;
	}
	
	/**
	 *
	 * @param string $advice
	 */
	function setAdvice($advice) {
		$this->advice = $advice;
	}
	
	/**
	 *
	 * @return string
	 */
	function getJptype() {
		return $this->jptype;
	}
	
	/**
	 *
	 * @param string $type
	 */
	function setJptype($type) {
		$this->jptype = $type;
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
	 * @return string
	 */
	function getCode() {
		return $this->code;
	}
	
	/**
	 *
	 * @param string $code
	 */
	function setCode($code) {
		$this->code = $code;
	}
	
	/**
	 *
	 * @return bool
	 */
	function isReturning() {
		return $this->isReturning;
	}
	
	/**
	 *
	 * @param bool $isReturning
	 */
	function setReturning($isReturning) {
		$this->isReturning = $isReturning;
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
	 * @param string $joinpoint
	 */
	function setJoinpoint($joinpoint) {
		$this->joinpoint = $joinpoint;
	}
}

?>