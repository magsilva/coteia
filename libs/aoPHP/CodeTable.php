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
require_once("AdviceTable.php");

class CodeTable {
	/**
	 * linked list of codesymbol objects
	 * @access private
	 */
	var $table;
	
	/**
	 * Constructor
	 */
	function CodeTable(){
	  $this->table = array();
	}
	
	/**
	 *
	 * @param CodeSymbol $newcode
	 */
	function addCode($newCode){
		$this->table[] = $newCode;
	}
	
	/**
	 *
	 * @param string $name
	 * @return bool
	 */
	function doesReturn($name){
		for($i = 0;$i < count($this->table);$i++){
			$temp = $this->table[$i];
	
			if(strtolower($temp->getJoinpoint()) == strtolower($name)){
				return $temp->doesReturn();
			}
		}
		return FALSE;
	}
	
	/**
	 *
	 * @param string $sig
	 * @return bool
	 */
	function hasSig($sig){
		for($i = 0;$i<count($this->table);$i++){
			$temp = $this->table[$i];

			if(strtolower($temp->getSignature()) == strtolower($sig)){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 *
	 * @param string $sig
	 * @return bool
	 */
	function hasSignature($sig){
		for($i = 0;$i<count($this->table);$i++){
			$temp = $this->table[$i];
			if($temp->getSignature() == $sig){
				return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 *
	 * @param string $name
     * @param bool $val
	 */
	function updateSigReturn($sig, $val){
		for($i = 0;$i<count($this->table);$i++){
			$temp = $this->table[$i];
			if($temp->getSignature() == $sig){
				$temp->setReturn($val);
			}
		}
	}
	
	/**
	 *
	 */
	function printTable(){
		echo "Code Table:\n";
		for($i = 0;$i<count($this->table);$i++){
			$temp = $this->table[$i];
			echo "  - ". $temp->getSignature(). " - " . $temp->doesReturn(). "\n";
		}
	}
	
	/**
	 *
	 * @param string $line
	 * @return string
	 */
	function scanLine($line){
		for($i = 0;$i<count($this->table);$i++){
			$temp = $this->table[$i];
			
			if(strpos($line, "function") === FALSE){
				$symbol_name = $temp->getName();
				
				if($symbol_name && strpos($line, $symbol_name) !== FALSE){
					$line = str_replace($symbol_name, "aophp_$symbol_name", $line);
				}
			}
		}
		return $line;
	}
	
	/**
	 * Generate Aspect Functions
	 *
	 * @param AdviceTable $at
	 * @return all the functions as a single String to be written to the file.
	 */
	function genAspectFuncs($at){
		$functions = "";
		$curFunc = "";
		$returns = FALSE;
		
		$size = count($this->table);
		// Loop Through CodeTable getting Functions
		for($i = 0;$i < $size;$i++){
			$temp = $this->table[$i];
			// Does Return
			$returns = $temp->doesReturn();
			// Define Function
			$curFunc .= "function aophp_". $temp->getSignature(). " {\n";
			// Insert Before Advice
			if($at->hasAdvice($temp->getSignature(), "before")){
				$curFunc .= "\n//Before Advice\n";
				$curFunc .= $at->getAdviceCode($temp->getSignature(), "before");
			}
	
			// Around Advice
			if($at->hasAdvice($temp->getSignature(), "around")){
				if($returns){
					// Around Advice, Returns
					//Call Function to Warp Advice (incase of Proceed or Return)
					$curFunc .= "\n// Around Advice\n";
					$curFunc .= $this->aroundReturn($at->getAdviceCode($temp->getSignature(), "around"), $temp->getName());
					//$curFunc .= $at->getAdviceCode($temp->getSignature(), "around");
					$curFunc .= "\n";	
				}else{
					// Around Advice, No Return
					// Call Function to Warp Advice (incase of Proceed)
					$curFunc .= "\n// Around Advice\n";
					$curFunc .= $this->aroundNoReturn($at->getAdviceCode($temp->getSignature(), "around"), $temp->getName());
					//$curFunc .= $at->getAdviceCode($temp->getSignature(), "around");
					$curFunc .= "\n";
				}
			}else{
				if($returns){
					// No Around Advice, Returns
					$curFunc .= "\n// No Around\n";
					$curFunc .= "\$temp = " . $temp->getSignature() . ";";
				}else{
					// No Around Advice, No Return
					$curFunc .= "\n// No Around\n";
					$curFunc .= $temp->getSignature().";";
				}
			}
			// Insert After Advice
			if($at->hasAdvice($temp->getSignature(), "after")){
				$curFunc .= "\n//After Advice\n";
				$curFunc .= $at->getAdviceCode($temp->getSignature(), "after");
			}
			// Return If Needed
			if($returns){
				$curFunc .= "return \$temp;\n";
			}
			// Close Function
			$curFunc .= "\n}";
			$functions .= "\n\n$curFunc";
			$curFunc = "";
		}

		return $functions;
	}
	
	/**
	 *
	 * @access private
	 * @param string $advice
	 * @param string $funcName
	 * @return string
	 */
	function aroundNoReturn($advice, $funcName){
		// The word proceed in any Strings or Comments will be replaced
		// This could cause an Issues with Printing the correct Strings
		// Will need to Change this in a Later Version
		return str_replace("proceed", $funcName, $advice);
	}

	/**
	 *
	 * @access private
	 * @param string $advice
	 * @param string $funcName
	 * @return string
	 */
	function aroundReturn($advice, $funcName){
		// The word proceed or return in any Strings or Comments will be replaced
		// This could cause an Issue with Printing the correct Strings
		// Will need to Change this in a Later Version
		$temp = str_replace("proceed","\$temp = $funcName", $advice);
		return str_replace("return","\$temp = ", $temp);
	}
}

?>