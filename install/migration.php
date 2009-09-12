<?php
  
include_once( dirname(__FILE__) . '../../function.php.inc' );

$i = 0;

function unhtmlentities( $string )
{
   $trans_tbl = get_html_translation_table( HTML_ENTITIES );
   $trans_tbl = array_flip( $trans_tbl );
   return strtr( $string, $trans_tbl );
}


function prepare_content_callback( $text, $element )
{
	$pattern = "/<br\s*\/?>/i";
	$text = preg_replace( $pattern, "", $text );

	//pre-formatado: nao faz parsing e tags sao mostradas na tela
	if ( $element === "pre" ) {
		$text = eregi_replace( "<", "&lt;", $text );
		$text = eregi_replace( ">", "&gt;", $text );
	}
	
	return $text;
}

function prepare_content( $text, $element )
{
	$pattern = "/(<$element\s*.*>)(.*)(<\/$element\s*>)/iUes";
	$result = preg_replace( $pattern, "'\\1' . prepare_content_callback('\\2', \$element) . '\\3'", $text );

	return $result;
}

function br2nl( $text )
{
	$elements = array();
	$elements[] = "table";
	$elements[] = "pre";
	$elements[] = "ul";
	$elements[] = "ol";
	
	foreach ( $elements as $element ) {
		$text = prepare_content( $text, $element );
	}

	return $text;
}

function test( $text )
{
	global $i;

	echo ++$i;
	echo "\n\tBefore: " . $text;
	echo "\n\tAfter: " . br2nl( $text );
	echo "\n\n";
}


test( "<table><br/></table>" );
test( "<table><br></table>" );
test( "<table><br /></table>" );
test( "<table><br ></table>" );

test( "<table> <br/></table>" );
test( "<table> <br></table>" );
test( "<table> <br /></table>" );
test( "<table> <br ></table>" );

test( "<table><br/> </table>" );
test( "<table><br> </table>" );
test( "<table><br /> </table>" );
test( "<table><br > </table>" );

test( "<table> <br/> </table>" );
test( "<table> <br> </table>" );
test( "<table> <br /> </table>" );
test( "<table> <br > </table>" );

test( "<table>\n<br/></table>" );
test( "<table>\n<br></table>" );
test( "<table>\n<br /></table>" );
test( "<table>\n<br ></table>" );

test( "<table><br/>\n</table>" );
test( "<table><br>\n</table>" );
test( "<table><br />\n</table>" );
test( "<table><br >\n</table>" );

test( "<table>\n<br/>\n</table>" );
test( "<table>\n<br>\n</table>" );
test( "<table>\n<br />\n</table>" );
test( "<table>\n<br >\n</table>" );



?>
