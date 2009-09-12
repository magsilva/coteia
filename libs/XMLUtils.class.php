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

Copyright (C) 2008 Marco Aurelio Graciotto Silva <magsilva@gmail.com>
*/

public class XMLUtils
{

	/**	
	* Process an XML file with the XSL.
	*
	* @param xml [string] The XML document's filename.
	* @param xsl [string] The XSL document's filename.
	* @param output [string] The output filename or NULL if the result must
	* be returned by the function.
	* @param parameters [array] Array of parameters to be fed to the XSLT
	* processor.
	* @param encoding [string] Character encoding to be used.
	*
	* @return The filename for the file with the results or the output if
	* the $output parameter is set to 'null'.
	*/
	public static function transform($xml, $xsl, $output, $parameters = null, $encoding = null)
	{
		$xh = xslt_create();
		if ($encoding != null) {
			xslt_set_encoding($xh, $encoding);
		}
		
		@define('XSLT_SABOPT_DISABLE_STRIPPING', 1);
		@define('XSLT_SABOPT_DISABLE_ADDING_META', 1);
		$result = xslt_process($xh, $xml, $xsl, $output, $arguments, $parameters);
		if (! $result && xslt_errno($xh) > 0) {
			$message = xslt_error($xh);
			xslt_free($xh); 
			throw new Exception($message);
		}
		xslt_free($xh);

		return $result;
	}

	/**
	* Check if a text is a valid XML document.
	*
	* @param xml_document [string] Text to be evaluated.
	* 
	* @returns True if it's valid. If an error was found, an array with the
	* error message and document processed is returned. The first element in the
	* array is the error_message, the second is the document's content.
	*/
	function check_xml_document($xml_document)
	{
		$xml_parser = xml_parser_create_ns();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
		$result = xml_parse($xml_parser, $xml_document);
		if ($result == false) {
			$error_code = xml_get_error_code($xml_parser);
			$line_number = xml_get_current_line_number($xml_parser);
			$column_number = xml_get_current_column_number($xml_parser);
			$result = array ();
			$result[] = sprintf(_("Line %d, Column %d"), $line_number, $column_number) . ": " . xml_error_string($error_code) . " (" . _("Error code: ") . $error_code . ")";
			$result[] = $xml_document;
		} else {
			$result = true;
		}
		xml_parser_free($xml_parser);

		return $result;
	}
	
}
?>