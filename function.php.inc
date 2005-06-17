<?php

include_once( "config.php" );
include_once( "plugins/cvs/cvs-api.php.inc" );

/**
* Connect to the SGBD and select the CoTeia's database.
*
* @return The database handler.
*/
function coteia_connect()
{
	global $dbhost, $dbuser, $dbpass, $dbname;

	$db_handler = mysql_pconnect( $dbhost, $dbuser, $dbpass );
	if ( !$db_handler ) {
		show_error( _( "Connection to the database has failed. Please, try again later (within some minutes). If the problem persits, contact the system's administrator." ), 1 );
	}

	$result = mysql_select_db( $dbname, $db_handler );
	if ( !$result ) {
		show_error( _( "Connection to the database has failed. Please, try again later (within some minutes). If the problem persits, contact the system's administrator." ), 1 );
	}

	return $db_handler;
}


/**
* Authenticate a user into a given swiki and start a session.
*
* @param username A string representing the name of the user
* @param password A plain text string representing the user's password. This
* string will be digested (md5) for authentication purposes.
* @param swiki_id An integer for the swiki identificator.
*
* @return Returns TRUE if user could successfully login, FALSE otherwise
* (probably due to wrong password).
*/
function coteia_login_swiki( $username, $password, $swiki_id )
{
	session_name( "coteia" );
	session_start();

	// Prepare the query parameters.
	$swiki_id = extract_swiki_id( $swiki_id );
	$username = mysql_escape_string( $username );
	$password = mysql_escape_string( $password );

	// Check if the user has already been authenticated.
	if ( isset( $_SESSION[ "swiki_" . $swiki_id ] ) && $_SESSION[ "swiki_" . $swiki_id ] === true ) {
		return true;
	} else {
		coteia_connect();
		// The swiki_id 0 is the admin's swiki.
		if ( $swiki_id == 0 ) {
			$query = "select email from admin where login='$username' and pass=MD5('$password')";
		} else {
			$query  = "select status from swiki where id='$swiki_id' and binary username='$username' and password=md5('$password')";
		}
		$result = mysql_query( $query );
		if ( mysql_num_rows( $result ) == 1 ) {
			$_SESSION[ "swiki_" . $swiki_id ] = true;
			$_SESSION[ "swiki_test_" . $swiki_id ] = "ok";
			return true;
		} else {
			return false;
		}
	}
}


/**
* Logout from CoTeia.
*/
function coteia_logout()
{
	session_commit();
	session_unset();
	session_destroy();
}


/**
* Check if any character data inside an XML element should be replaced by HTML
* entities.
*
* @return Returns FALSE if the XML (HTML) element is "pre", otherwise returns
* TRUE.
*/
function can_replace_chars_within( $xml_element )
{
	$result = true;
	// RE: (<namespace>:)? "pre" ((\s)* <atribute>)* (\s)*
  if ( preg_match( "/^(.*:)?pre((\s)*(.)*)*(\s)*$/i", $xml_element ) > 0 ) {
		$result = false;
	}
	return $result;
}


/**
* Replace special chars by their respective HTML entities.
*
* @param text Content (as a string) to be processed.
* @return Content (as a string) with special characters replaced by
* HTML entities.
*/
function replace_chars( $text )
{
	$inside_tag = false;
	$inside_comment = false;
	$elements = array();
	$current_element = "";
	$save_element_name = false;

	$new_text = "";
	for ( $i = 0; $i < strlen( $text ); $i++ ) {
		// Add comment as it is.
		if ( $text{ $i } == "<" && $text{ $i + 1 } == "!" && $text{ $i + 2 } == "-" && $text{ $i + 3 } == "-" ) {
			$new_text .= $text{ $i } . $text{ $i + 1 } . $text{ $i + 2 } . $text{ $i + 3};
			$i += 3;
			$inside_comment = true;
			continue;
		}

		if ( $inside_comment ) {
			if ( $text{ $i } == "-" && $text{ $i + 1 } == "-" && $text{ $i + 2 } == ">" ) {
				$inside_comment = false;
				$new_text .= $text{ $i } . $text{ $i + 1 } . $text{ $i + 2 };
				$i += 2;
			} else {
				$new_text .= $text{ $i };
			}
			continue;
		}

		// If haven't found the end tag for "pre" element, keep procesing.
		if ( $current_element == "pre" ) {
			if ( ( ( $i + strlen( "</pre>" ) ) < strlen( $text ) ) &&
			 ( $text{ $i } != "<"
			 || $text{ $i + 1 } != "/"
			 || $text{ $i + 2 } != "p"
			 || $text{ $i + 3 } != "r"
			 || $text{ $i + 4 } != "e"
			 || $text{ $i + 5 } != ">" ) ) {
				$new_text .= htmlspecialchars( $text{ $i } );
				continue;			
			}
		}

		// Detects the possible start of a tag.
		if ( $inside_tag == false && $text{ $i } == "<" ) {
			// Check if it's a element's start tag or empty element's tag or if it's an end tag:
			// * It'll be a tag if the "<" is not followed by a blank space.
			// * It'll be and end tag if the "<" if followed by an "/", otherwise it's as start or
			//   empty element's tag (or an error).
			// Anything different from that is a invalid element definition and will be catched
			// later by the XML parser.
			if ( strlen( trim( $text{ $i + 1 } ) ) != 0 ) {
				$inside_tag = true;
				$new_text .= $text{ $i };

				if ( $text{ $i + 1 } == "/" ) {
					// We're in an end tag, so clean the current_element:
					$current_element = "";
					$save_element_name = false;
				} else {
					// If we're not in a close tag, we are in a start tag or empty tag.
					$save_element_name = true;
					array_push( $elements, "" );
				}
				continue;
			}
		}

		// Detects the possible end of a tag.
		if ( $inside_tag == true ) {
			if ( $text{ $i } == ">" ) {
				$new_text .= $text{ $i };
				$inside_tag = false;
				// If it was an end tag (in other words, signalizing the end of an element),
				// pop the element's name.
				if ( $save_element_name == false ) {
					array_pop( $elements );
				} else {
					$current_element = array_pop( $elements );
					array_push( $elements, $current_element );
				}
				continue;
			} else {
				// We're inside a tag yet, let's keep saving the element's name and attributes.
				if ( $save_element_name ) {
					$element = array_pop( $elements );
					array_push( $elements, $element . $text{ $i } );
				}
			}
		}

		// Replace chars.
		if ( ! $inside_tag ) {
			$new_text .= htmlspecialchars( $text{ $i } );
		} else {
			$new_text .= $text{ $i };
		}
	}
	return $new_text;
}


/**
* Clean the wikipage's raw data from futile PHP's stuff.
*
* @param wikipage_raw [array] Data to be cleaned up.
* @return [array] Cleant data.
*/
function prepare_for_anything( $wikipage_raw )
{
	// Strip slashes if magic_quotes_gpc (auto adding slashes) is active.
	if ( get_magic_quotes_gpc() == 1 ) {
		$wikipage_raw[ "indexador" ] = stripslashes( $wikipage_raw[ "indexador" ] );
		$wikipage_raw[ "conteudo" ] = stripslashes( $wikipage_raw[ "conteudo" ] );
		$wikipage_raw[ "titulo" ] = stripslashes( $wikipage_raw[ "titulo" ] );
		$wikipage_raw[ "autor" ] = stripslashes( $wikipage_raw[ "autor" ] );
		$wikipage_raw[ "kwd1" ] = stripslashes( $wikipage_raw[ "kwd1" ] );
		$wikipage_raw[ "kwd2" ] = stripslashes( $wikipage_raw[ "kwd2" ] );
		$wikipage_raw[ "kwd3" ] = stripslashes( $wikipage_raw[ "kwd3" ] );
	}

	$wikipage_raw[ "indexador" ] = trim( $wikipage_raw[ "indexador" ] );
	$wikipage_raw[ "conteudo" ] = trim( $wikipage_raw[ "conteudo" ] );
	$wikipage_raw[ "titulo" ] = trim( $wikipage_raw[ "titulo" ] );
	$wikipage_raw[ "autor" ] = trim( $wikipage_raw[ "autor" ] );
	$wikipage_raw[ "kwd1" ] = trim( $wikipage_raw[ "kwd1" ] );
	$wikipage_raw[ "kwd2" ] = trim( $wikipage_raw[ "kwd2" ] );
	$wikipage_raw[ "kwd3" ] = trim( $wikipage_raw[ "kwd3" ] );

	return $wikipage_raw;
}

/**
* Prepare the data for database processing.
*
* @param wikipage_raw [array]
* @return [array] Indexed array with processed content.
*/
function prepare_for_db( $wikipage_raw )
{
	$wikipage_raw = prepare_for_anything( $wikipage_raw );

	$wikipage_db[ "index" ] = mysql_escape_string( $wikipage_raw[ "indexador" ] );
	$wikipage_db[ "content" ] = mysql_escape_string( $wikipage_raw[ "conteudo" ] );
	$wikipage_db[ "title" ] = mysql_escape_string( $wikipage_raw[ "titulo" ] );
	$wikipage_db[ "author" ] = mysql_escape_string( $wikipage_raw[ "autor" ] );
	$wikipage_db[ "keyword1" ] = mysql_escape_string( $wikipage_raw[ "kwd1" ] );
	$wikipage_db[ "keyword2" ] = mysql_escape_string( $wikipage_raw[ "kwd2" ] );
	$wikipage_db[ "keyword3" ] = mysql_escape_string( $wikipage_raw[ "kwd3" ] );

	return $wikipage_db;
}

/**
* Prepare the data for web.
*
* @param wikipage_raw [array]
* @return [array] Indexed array with processed content.
*/
function prepare_for_xml( $wikipage_raw )
{
	$wikipage_raw = prepare_for_anything( $wikipage_raw );

	$wikipage_xml[ "index" ] = replace_chars( $wikipage_raw[ "indexador" ] );
	$wikipage_xml[ "title" ] = replace_chars( $wikipage_raw[ "titulo" ] );
	$wikipage_xml[ "author" ] = replace_chars( $wikipage_raw[ "autor" ] );
	$wikipage_xml[ "content" ] = replace_chars( $wikipage_raw[ "conteudo" ] );
	$wikipage_xml[ "keyword1" ] = replace_chars( $wikipage_raw[ "kwd1" ] );
	$wikipage_xml[ "keyword2" ] = replace_chars( $wikipage_raw[ "kwd2" ] );
	$wikipage_xml[ "keyword3" ] = replace_chars( $wikipage_raw[ "kwd3" ] );
	$wikipage_xml[ "raw_content" ] = $wikipage_raw[ "conteudo_original" ];
	return $wikipage_xml;
}


/**
* Execute the XML processing via XSLT and save the data.
*
* @param id [integer] The wikipage id.
* @param xml_document [string] The wikipage's data, as processed by @link prepare_xml_document
* @param xsl_name [array] The name of the stylesheets (XSL) to be used (string). For example, if
* you want to use the "test123.xsl", you should pass [ "test123" ] as parameter.
* @param format [string] The final output format. It sets the last xslt to be applied in the
* document processing pipeline.
*
* @return Returns TRUE if the XML file was correctly processed. Otherwise, returns a message
* with details with errors' details as reported by the XSLT engine.
*/
function process_xml_document( $id, $xml_document, $xsl_names, $format )
{
	global $XML_DIR, $OUTPUT_DIR, $XSL_DIR, $DEFAULT_FILE_PERMISSION;

	$xml = $XML_DIR . "/" . $id  . ".xml";

	if ( is_file( $xml ) ) {
		// If we are the file's owner, this error can be corrected on the fly, so let's do it.
		@chmod( $xml, $DEFAULT_FILE_PERMISSION );
	}

	$fp = @fopen( $xml , "w+" );
	if ( $fp === false ) {
		show_error( _( "An error was found when processing the wikipage (couldn't save the wikipage's XML document )." ) );
	}
	fputs( $fp, $xml_document );
	fclose( $fp );

	$xsl_names[] = $format;
	$xml_temp = $xml;

	foreach ( $xsl_names as $xsl ) {
		$result = apply_xsl( $xml_temp, $XSL_DIR . "/" . $xsl . ".xsl" );
		if ( is_array( $result ) ) {
			$result = implode( $result, "\n" );
			return $result;
		}
		if ( $xml_temp != $xml ) {
			unlink( $xml_temp );
		}
		$xml = $result;
	}

	@mkdir( $OUTPUT_DIR );
	@mkdir( $OUTPUT_DIR . "/" . $format );
	$output = $OUTPUT_DIR . "/" . $format . "/" . $id . "." . $format;
	rename ( $xml, $output );

	return true;
}


/**	
* Process an XML file with the XSL passed as argument.
*
* @param xml [string] The XML document's filename.
* @param xsl [string] The XSL document's filename.
*
* @return The filename for the file with the results or an array with the error
* messages.
*/
function apply_xsl( $xml, $xsl )
{
	global $PATH_JAVA, $PATH_XT;

	$result = array();
	$output = tempnam( "/tmp", "coteia" );

	if ( ! function_exists( "xslt_create" ) ) {
		// XT usage: [path_java] -classpath [classpath_xt] com.jclark.xsl.sax.Driver [xml] [xsl] [html]
		$cmd_line = "$PATH_JAVA -cp \"$PATH_XT\" -Dcom.jclark.xsl.sax.parser=com.jclark.xml.sax.CommentDriver com.jclark.xsl.sax.Driver $xml $xsl $output 2>&1";
		// The command below can also be used (it's the build-in Java XSLT engine).
		// $cmd_line = "$PATH_JAVA org.apache.xalan.xslt.Process -in $xml -xsl $xsl -out $output 2>&1";
		exec( $cmd_line, $var_array, $status );
		if ( $status == 0 ) {
			$result = $output;
		} else {
			$result = $var_array;
		}
	} else {
		$xh = xslt_create();
		xslt_set_encoding( $xh, "ISO-8859-1" );
		@define( "XSLT_SABOPT_DISABLE_STRIPPING", 1 );
		@define( "XSLT_SABOPT_DISABLE_ADDING_META", 1 ); 
		$result = @xslt_process( $xh, $xml, $xsl, $output );
		if ( !$result && xslt_errno( $xh ) > 0 ) {
			$result[] = xslt_error( $xh );
		} else {
			$result = $output;
		}
		xslt_free( $xh );
	}

	return $result;
}


/**
* Add pretty icons to each upload reference. The icons reflects the file type.
*
* @param content [string] The wikipage's content data.
*
* @return [string] The new wikipage's content data.
*/
function plugin_upload( $content )
{
	$filetypes = array();
	$filetypes[ ".pdf" ] = "1";
	$filetypes[ ".htm" ] = "2";
	$filetypes[ ".html" ] = "2";
	$filetypes[ ".doc" ] = "3";
	$filetypes[ ".ppt" ] = "4";
	$filetypes[ ".zip" ] = "5";

	foreach ( array_keys( $filetypes ) as $i ) {
		$pattern = "/(<upl)(\s*)(file=)(.*" . $i . ")(\s*)(\/?>)/i";
		$replacement = "$1$2$3$4 id=\"" . $filetypes[ $i ] . "\" $5$6";
		$content = preg_replace( $pattern, $replacement, $content );
	}

	return $content;
}


/**
* Create automatically the links to another wikipages.
*
* @param wikipage_id [integer] Wikipage's identifier.
* @param content [string] Wikipage's content.
*
* @return Content with links to wikipages.
*/
function prepare_camelcase_links( $wikipage_id, $content )
{
	$pattern = "/([A-Z][a-z]+[A-Z]\w*)/";

	// Trick to pass a hint to the callback funtion.
	$GLOBALS[ "callback_hint" ] = $wikipage_id;
	$result = preg_replace_callback( $pattern, "prepare_camelcase_links_callback", $content );
	unset( $GLOBALS[ "callback_hint" ] );

	return $result;
}

function prepare_camelcase_links_callback( $matches )
{
	global $callback_hint;

	$wikipage_index = trim( $matches[1] );
	$links = find_wikipage_link( $wikipage_index, $callback_hint );
	return prepare_wikipage_link( $links );
}



/**
* Create automatically the links to another wikipages.
* The algorithm is:
*  - If id=$ident.x && titulo=tupla[titulo], then link
*  - If the target hasn't been created, then add a [create] annotation.
*
* @param wikipage_id [integer] Wikipage's identifier.
* @param content [string] Wikipage's content.
*
* @return Content with links to wikipages.
*/
function prepare_links( $wikipage_id, $content )
{
	// "<lnk" (\s* swiki=$swiki_id)? \s* ">" \s* $wikipage_index \s* "</lnk" \s* ">"
	//$pattern = "/(<lnk)(\s*swiki=.*)+(\s*>)(\s*)(.*)(\s*)(<\/lnk\s*>)/i";
	$pattern = "/(<lnk\s*>)(.*)(<\/lnk\s*>)/iU";
	// Trick to pass a hint to the callback funtion.
	$GLOBALS[ "callback_hint" ] = $wikipage_id;
	$result = preg_replace_callback( $pattern, "prepare_links_callback", $content );
	unset( $GLOBALS[ "callback_hint" ] );

	return $result;
}

/**
function prepare_links_callback( $matches ) {
	global $callback_hint;

	$wikipage_index = $matches[5];
	if ( count( $matches[2] ) != 0 ) {
		$swiki_name = preg_replace( "\s*swiki=(.*)", "$1", $matches[2] );
		$links = find_wikipage_link( $wikipage_index, $callback_hint, $swiki_name );
	} else {
		$links = find_wikipage_link( $wikipage_index, $callback_hint );
	}
 return prepare_wikipage_link( $links );
}
*/

function prepare_links_callback( $matches )
{
	global $callback_hint;

	$wikipage_index = trim( $matches[2] );
	$links = find_wikipage_link( $wikipage_index, $callback_hint );
	return prepare_wikipage_link( $links );
}

/**
* Find for the requested wikipage.
*
* @param wikipage_index [string] The name of the wikipage.
* @param wikipage_id [string] The identifier of the wikipage one you
* are looking from for links.
* @param swiki_name [string] (optional) The name of the swiki the search
* will be restricted to.

* @return [array] Returns an array with the wikipage's found. If a hint
* was given and the search found it, its link will be the first array
* element (0). Every array entry is another array that holds some of the
* wikipage's data (wikipage_id, index, title, ctime, and author). If no
* wikipage is found, returns and empty array.
*/
function find_wikipage_link()
{
	if ( func_num_args() < 2 || func_num_args() > 3 ) {
		trigger_error( "Invalid number of arguments" );
	}

	$wikipage_index = func_get_arg( 0 );
	$swiki_id = extract_swiki_id( func_get_arg( 1 ) );
	if ( $swiki_id == false ) {
		return false;
	}

	coteia_connect();
	if ( func_num_args() == 3 ) {
    	$swiki_name = func_get_arg( 2 );
		$swiki_name = mysql_escape_string( $swiki_name );
		$query = "select id from swiki where titulo='$swiki_name'";
		$result = mysql_query( $query );
		if ( mysql_num_rows( $result ) == 0 ) {
    		$swiki_id = 0;
	  	} else {
	  		$tuple = mysql_fetch_array( $result );
			$swiki_id = $tuple[ "id" ];
		}
		mysql_free_result( $result );
	}

	$wikipage_links = array();

	$query = "select ident,indexador,titulo,data_ultversao,autor,id_sw FROM paginas,gets where binary indexador='" . mysql_escape_string( $wikipage_index ) . "' and paginas.ident=gets.id_pag";
	if ( $swiki_id != 0 ) {
		$query .= " and id_sw='$swiki_id'";
	}
	$result = mysql_query( $query );

	// This means it's a new wikipage.
	if ( mysql_num_rows( $result ) == 0 ) {
		$link = array();
		$link[ "wikipage_id" ] = "0";
		$link[ "index" ] = $wikipage_index;
		$link[ "swiki_id" ] = $swiki_id;
		$wikipage_links[] = $link;
	}

	// So we found some wikipages to link to.
	while ( $tuple = mysql_fetch_array( $result ) ) {
		$link = array();
		$link[ "wikipage_id" ] = $tuple[ "ident" ];
		$link[ "index" ] = $tuple[ "indexador" ];
		$link[ "title" ] = $tuple[ "titulo" ];
		$link[ "ctime" ] = $tuple[ "data_ultversao" ];
		$link[ "author" ] = $tuple[ "autor" ];
		$wikipage_links[] = $link;
	}
	mysql_free_result( $result );

	return $wikipage_links;
}

/**
* Prepare the HTML code that will replace the "lnk" element within
* a wikipage.
*
* @param links [array] The array returned by @link find_wikipage_link.
*
* @return [string] The HTML code that will link to the $links.
*/
function prepare_wikipage_link( $links )
{
	$i = $links[ 0 ];
	if ( $i[ "wikipage_id" ] == "0" ) {
		$result = ' <a href="edit.php?wikipage_id=0&amp;swiki_id=' . $i[ "swiki_id" ] . '&amp;index=' . rawurlencode( $i[ "index" ] ) . '">' . $i[ "index" ] . '[create]</a> ';
	} else {
		$ctime = strtotime( $i[ "ctime"  ] );
		$ctime =  _( "Last modified in ") . date( "j/n/Y" , $ctime ) . _( " at " ) . date( "H:i", $ctime );
		$result = ' <a href="show.php?wikipage_id=' . $i[ "wikipage_id" ] . '" onMouseOver="window.status=\'' . $ctime . '; return true\'">' . $i[ "index" ] . '</a> ';
	}

	return $result;
}


/**
* Compile the wikipage. The data will be retrived from the database, an XML
* file will be created and the HTML file will be compiled (actually, the XML
* will be processed using an XSLT).
*
* @param wikipage_id [integer] The wikipage's identifier.
* @param format [string] The output format. Actually this sets the final XSLT
* to be applied.
*
* @return Returns false if the wikipage couldn't (1) be retrieved from database.
* If the XML processing failed, it returns a string with the error messages
* received from @link process_xml_document. If successfull, returns TRUE.
*/
function compile_wikipage( $wikipage_id, $format )
{
	global $CVS_WIKIPAGE_MODULE, $STYLESHEETS;

	if ( check_wikipage_id( $wikipage_id ) == false ) {
		return false;
	}

	// Retrieve wikipage's raw data.
	coteia_connect();
	$query = "select * FROM paginas where ident='$wikipage_id'";
	$result = mysql_query( $query );
	if ( mysql_num_rows( $result ) == 0 ) {
		return false;
	}
	$wikipage_raw = mysql_fetch_array( $result );
	mysql_free_result( $result );

	$wikipage_raw[ "conteudo_original" ] = "<![CDATA[" . $wikipage_raw[ "conteudo" ] . "]]>";

	//	$tuple[ "conteudo" ] = note( $tuple[ "conteudo" ] );
	$wikipage_raw[ "conteudo" ] = prepare_camelcase_links( $wikipage_id, $wikipage_raw[ "conteudo" ] );
	$wikipage_raw[ "conteudo" ] = prepare_links( $wikipage_id, $wikipage_raw[ "conteudo" ] );
	$wikipage_raw[ "conteudo" ] = plugin_upload( $wikipage_raw[ "conteudo" ] );

	$wikipage_xml = prepare_for_xml( $wikipage_raw );

	// Prepare swiki and wikipages identificators.
	$swiki_id = extract_swiki_id( $wikipage_id );
	$wikipage_xml[ "wikipage_id" ] = $wikipage_id;
	$wikipage_xml[ "swiki_id" ] = $swiki_id;

	// Prepare back-reference links.
	$linksto = array();
	if ( $wikipage_id == $swiki_id ) {
		$linksto[ 0 ] = _( "List of swikis" );
	}
	// <lnk> links
	$query = "select ident,titulo from paginas where ( ident='$swiki_id' or ident like '$swiki_id.%') and ident<>'$wikipage_id' and binary conteudo like '%<lnk%>" . mysql_escape_string( $wikipage_raw[ "indexador" ] ) . "</lnk%>%'";
	$result = mysql_query( $query  );
	while ( $tuple = mysql_fetch_array( $result ) ) {
		$linksto[ $tuple[ "ident" ] ] =  $tuple[ "titulo" ];
	}
	mysql_free_result( $result );
	// CamelCase links
	if ( preg_match ( "/([A-Z]+[a-z]+[A-Z]\w*)/", $wikipage_raw[ "indexador" ] ) == 1 ) {
		$query = "select ident,titulo from paginas where ( ident='$swiki_id' or ident like '$swiki_id.%') and ident<>'$wikipage_id' and binary conteudo like '%" . $wikipage_raw[ "indexador" ] . "%'";
		$result = mysql_query( $query  );
		while ( $tuple = mysql_fetch_array( $result ) ) {
			$linksto[ $tuple[ "ident" ] ] =  $tuple[ "titulo" ];
		}
		mysql_free_result( $result );
	}

	$wikipage_xml[ "links" ] = $linksto;

	// Set the page's lock status.
	if ( $wikipage_raw[ "pass" ] != NULL ) {
		$wikipage_xml[ "lock" ] = 1;
	} else {
		$wikipage_xml[ "lock" ] = 0;
	}

	// Prepare links to another services.
	$query = "select id_ann,id_chat,id_eclass from swiki where id='$swiki_id'";
	$result = mysql_query( $query );
	$tuple = mysql_fetch_array( $result );
	mysql_free_result( $result );

	$wikipage_xml[ "annotation" ] = $tuple[ "id_ann" ];
	$wikipage_xml[ "chat" ] = $tuple[ "id_chat" ];
	$wikipage_xml[ "eclass" ] = $tuple[ "id_eclass" ];

	$xml_document = prepare_xml_document( $wikipage_xml );

	$result = process_xml_document( $wikipage_id, $xml_document, $STYLESHEETS, $format );

	if ( $result === true ) {
		// Update CVS (this is not critical, so we don't return the cvs result.
		$result = false;
		$filename = wikipage2cvs( $wikipage_id );
		$result = cvs_update( $CVS_WIKIPAGE_MODULE, $filename );
		// If we cannot update the file, try to add it.
		if ( $result === false ) {
			$result = cvs_add( $CVS_WIKIPAGE_MODULE, $filename );
		}
		return true;
	}

	return $result;
}

/**
* Extract the parent wikipage identificator.
*
* @param ident [integer] A wikipage identificator.
*
* @return Returns the parent's wikipage if successful, FALSE otherwise.
*/
function extract_parent_wikipage( $ident )
{
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	}

	$childest_id = strrpos( $ident, "." );
	$parent_id = $ident; // This holds true if the wikipage is the main swiki's page.
	if ( $childest_id != false ) {
		$parent_id = substr( $ident, 0, $childest_id );
  }

	return $parent_id;
}


/**
* Extract the swiki identificator from a full wikipage identificator.
*
* @param ident [integer] A wikipage identificator.
*
* @return Returns the swiki's identifier if successful, FALSE otherwise.
*/
function extract_swiki_id( $ident )
{
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	}

	if ( strpos( $ident, "." ) ) {
		$get_swiki = explode( ".", $ident );
    $swiki_id = $get_swiki[ 0 ];
  } else {
    $swiki_id = $ident;
  }

	return $swiki_id;
} 


/**
* Check if a wikipage identificator is valid. It's checked againt the
* following regular expression:  id ::= [0-9]+ ( "." [0-9] )*
*
* @param ident [integer] A wikipage identificator.
*
* @return Returns TRUE if the identifier is valid, FALSE otherwise.
*/
function check_wikipage_id( $ident )
{
	return ( preg_match( "/^[\d]+(\.[\d]+)*$/", $ident ) > 0 ) ? true : false;
}

/**
* Check if a swiki identificator is valid. It's checked againt the
* following regular expression:  id ::= [0-9]+
*
* @param ident [integer] A swiki identifier.
*
* @return Returns TRUE if the identifier is valid, FALSE otherwise.
*/
function check_swiki_id( $ident )
{
	return ( preg_match( "/^[\d]+$/", $ident ) > 0 ) ? true : false;
}

/**
* Extract the wikipage identificator from a possibly valid wikipage identificator.
*
* @param ident [integer] A wikipage identificator.
*
* @return Returns the wikipage's identifier if successfull, FALSE otherwise.
*/
function extract_wikipage_id( $ident )
{
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	} else {
		return $ident;
	}
}


/**
* Check if a string matches the start tag for the given element.
*
* @param content The data to be analysed.
* @param type The element's name to be searched.
*
* @return TRUE if matches, FALSE otherwise.
*/
function match_start_tag( $content, $type )
{
	return ( preg_match( "/.*<$type\s.*>.*/", $content ) > 0 ) ? true : false;
}


/**
* Check if a string matches the end tag for the given element type.
*
* @param content The data to be analysed.
* @param type The element's name to be searched.
*
* @return TRUE if matches, FALSE otherwise.
*/
function match_end_tag( $content, $type )
{
	return ( preg_match( "/.*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}


/**
* Check if a string matches the tag for the given empty element type.
*
* @param content The data to be analysed.
* @param type The element's name to be searched.
*
* @return TRUE if matches, FALSE otherwise.
*/
function match_empty_tag( $content, $type )
{
	return ( preg_match( "/.*<$type\s*\/>.*/", $content ) > 0 ) ? true : false;
}


/**
* Check if the content holds an element type with the specified character data.
*
* @param content The data to be analysed.
* @param type The element's name to be searched.
* @param cdata The cdata to be searched.
*
* @return TRUE if matches, FALSE otherwise.
*/
function match_tag( $content, $type, $cdata )
{
	return ( preg_match( "/.*<$type\s.*>\s*$cdata\s*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Handle errors found.
*
* @param error_message [string] The error message, as will be shown to the user.
* @param error_code [int] The error message identifier (if applicable).
*
* @return This function does not return, it exits, sending the user the contents
* of the file "err.php.inc".
*/
function show_error()
{
	if ( func_num_args() < 1 || func_num_args() > 2 ) {
		trigger_error( "Invalid number of arguments" );
	}

	$error_message = func_get_arg( 0 );
	if ( func_num_args() == 2 ) {
		$error_code = func_get_arg( 1 );
	}

	global $ADMIN, $ADMIN_EMAIL, $IMAGES_DIR;

  include( "err.php.inc" );
	exit( 1 );
}


/**
* Update a wikipage view (XHTML) if the current XHTML is outdated.
*
* @param wikipage_id [integer] Wikipage identifier.
* @param format [string] The output format to be used. Actually, this sets the
* XSLT to be applied.
*
* @return Returns TRUE if the page has been successfuly updated, FALSE otherwise.
*/
function update_wikipage( $wikipage_id, $format )
{
	global $OUTPUT_DIR, $XML_DIR, $XSL_DIR;

	if ( check_wikipage_id( $wikipage_id ) == false ) {
		return false;
	}

	// You may set $update to true if you're debugging the application.
	$update = true;
	$result = true;
	$wikipage_output = $OUTPUT_DIR . "/" . $format . "/" . $wikipage_id . "." .$format;

	// If the file doesn't exist, an update is necessary.
	if ( $update !== true ) {
		if ( !is_file( $wikipage_output ) ) {
			$update = true;
		} else {
			$ctime = filectime( $wikipage_output );
		}
	}

	// Must update (or create the file) if database is newer than the current file.
	if ( $update !== true ) {
		coteia_connect();
		$query = "select data_ultversao from paginas where ident='$wikipage_id'";
		$result = mysql_query( $query );
		$tuple = mysql_fetch_array( $result );
		$date = strtotime( $tuple[ "data_ultversao" ] );
		if ( $ctime < $date ) {
			$update = true;
		}
	}

	// Must update (or create the file) if the XML file is newer than the current file.
	if ( $update !== true ) {
		$wikipage_xml = $XML_DIR . "/" . $wikipage_id . ".xml";
		if ( $ctime < filectime( $wikipage_xml ) ) {
			$update = true;
		}
	}

	// If the XSLT transform sheet was updated, the file must be updated too.
	if ( $update !== true ) {
		if ( $ctime < filectime( $XSL_DIR . "/" . $format . ".xsl" ) ) {
			$update = true;
		}
	}
	
	if ( $update === true ) {
		$result = compile_wikipage( $wikipage_id, $format );
	}

	return $result;
}

/**
* Prepare the XHTML's header.
*
* The stylesheet filename's pattern is:
*    <name> "-media_" <media_name> [ "-alternate" ] ".css"
*
* For example, a stylesheet "example" designed for a printer (so
* the media is "printer") shall be name as "example-media_printer.css".
* An alternate stylesheet, "skywatch", designed for projection, would
* be named as "skywatch-media_projection-alternate.css".
*
* @param title [string] The page's title.
*
* @returns [string] Returns the header.
*/
function get_header( $title )
{
	global $CSS_DIR, $SKIN, $IMAGES_DIR;

	$charset = "iso-8859-1";

	$css_files = glob( dirname(__FILE__) . "/" . $CSS_DIR . "/*.css" );

	$content = "<html>\n";
	$content .= "<head>\n";
	$content .= "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\" />\n";
	$content .= "\t<title>$title</title>\n";
	$content .= "\t<link rel=\"shortcut icon\" href=\"$IMAGES_DIR/logo.ico\" />\n";
	$content .= "\t<script type=\"text/javascript\" src=\"coteia.js\"></script>\n";

	/*
	* The possible medias are: 
	*  - screen (the default value), for presentation on non-paged computer screens;
	*  - print, for output to a printer;
	*  - projection, for projected presentations;
  *  - aural, for speech synthesizers;
  *  - braille, for presentation on braille tactile feedback devices;
  *  - tty, for character cell displays (using a fixed-pitch font);
	*  - tv, for televisions;
	*  - all, for all output devices.
	*/
	foreach ( $css_files as $filename ) {

		preg_match( "/.*\/(.*.css)$/", $filename, $result );
		$stylesheet = $result[ 1 ];

		$result = array();
		preg_match_all( "/(.*)-media_(.*)(-alternate)?.css$/", $stylesheet, $result, PREG_SET_ORDER );
		foreach ( $result as $match ) {
			$rel = "";
			$href = "";
			$media = "";
			if ( isset( $match[3] ) ) {
				$rel = "alternate-stylesheet";
			} else {
				$rel = "stylesheet";
			}
			$match[0] = $CSS_DIR . "/" . $match[0];
			$content .= "\t<link rel=\"$rel\" type=\"text/css\" href=\"$match[0]\" media=\"$match[2]\" title=\"$SKIN\"/>\n";
		}
	}

	return $content;
}


/**
* Check if a XML document compiled with the given parameters is a valid Coteia's XML document.
*
* @param wikipage_raw [array]
* @returns [boolean,array] True is the compiled XML document is valid. If an error was
* found, an array with the error message and document processed is returned. The first
* element in the array is the error_message, the second is the document's content.
*/
function validate_wikipage( $wikipage_id, $wikipage_raw )
{
	$wikipage_raw[ "conteudo_original" ] = "<![CDATA[" . $wikipage_raw[ "conteudo" ] . "]]>";
	$wikipage_raw[ "conteudo" ] = prepare_links( $wikipage_id, $wikipage_raw[ "conteudo" ] );
	$wikipage_raw[ "conteudo" ] = plugin_upload( $wikipage_raw[ "conteudo" ] );

	$wikipage_xml = prepare_for_xml( $wikipage_raw );

	// Prepare swiki and wikipages identificators.
	$swiki_id = extract_swiki_id( $wikipage_id );
	$wikipage_xml[ "wikipage_id" ] = $wikipage_id;
	$wikipage_xml[ "swiki_id" ] = $swiki_id;

	// Prepare back-reference links.
	$linksto = array();
	if ( $wikipage_id == $swiki_id ) {
		$linksto[ 0 ] = _( "List of swikis" );
	}
	$query = "select ident,titulo from paginas where ( ident='$swiki_id' or ident like '$swiki_id.%') and ident<>'$wikipage_id' and binary conteudo like '%<lnk%>" . mysql_escape_string( $wikipage_raw[ "indexador" ] ) . "</lnk%>%'";
	$result = mysql_query( $query  );
	while ( $tuple = mysql_fetch_array( $result ) ) {
		$linksto[ $tuple[ "ident" ] ] =  $tuple[ "titulo" ];
	}
	mysql_free_result( $result );
	$wikipage_xml[ "links" ] = $linksto;

	// Set the page's lock status.
	if ( $wikipage_raw[ "pass" ] != NULL ) {
		$wikipage_xml[ "lock" ] = 1;
	} else {
		$wikipage_xml[ "lock" ] = 0;
	}

	// Prepare links to another services.
	$query = "select id_ann,id_chat,id_eclass from swiki where id='$swiki_id'";
	$result = mysql_query( $query );
	$tuple = mysql_fetch_array( $result );
	mysql_free_result( $result );

	$wikipage_xml[ "annotation" ] = $tuple[ "id_ann" ];
	$wikipage_xml[ "chat" ] = $tuple[ "id_chat" ];
	$wikipage_xml[ "eclass" ] = $tuple[ "id_eclass" ];

	$xml_document = prepare_xml_document( $wikipage_xml );

	$result = check_xml_document( $xml_document );

	return $result;
}


/**
* Check if a text is a valid XML document.
*
* @param xml_document [string] Text to be evaluated.
* @returns True if it's valid. If an error was found, an array with the
* error message and document processed is returned. The first element in the
* array is the error_message, the second is the document's content.
*/
function check_xml_document( $xml_document )
{
	$xml_parser = xml_parser_create_ns();
	xml_parser_set_option( $xml_parser, XML_OPTION_CASE_FOLDING, 0 );
	$result = xml_parse( $xml_parser, $xml_document );
	if ( $result == false ) {
		$error_code = xml_get_error_code( $xml_parser );
		$line_number = xml_get_current_line_number( $xml_parser );
		$column_number = xml_get_current_column_number( $xml_parser );
		$result = array();
		$result[] = sprintf( _( "Line %d, Column %d" ), $line_number, $column_number ) . ": " . xml_error_string( $error_code ) . " (" .  _( "Error code: " ) . $error_code . ")";
		$result[] = $xml_document;
	} else {
		$result = true;
	}
	xml_parser_free( $xml_parser );

	return $result;
}


/*
* Prepare XML document for processing.
*
* @param wikipage [array] Processed wikipage data (@link process_xml_document).
*
* @return [string] Returns an XML document.
*/
function prepare_xml_document( $wikipage )
{
	$xml_document =  <<<END
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE coteia SYSTEM "coteia.dtd">
<page>
<id>{$wikipage[ "wikipage_id" ]}</id>
<lock>{$wikipage[ "lock" ]}</lock>
<ann_folder>{$wikipage[ "annotation" ]}</ann_folder>
<chat_folder>{$wikipage[ "chat" ]}</chat_folder>
<id_eclass>{$wikipage[ "eclass" ]}</id_eclass>
<sw_id>{$wikipage[ "swiki_id" ]}</sw_id>
END;

	foreach ( array_keys( $wikipage[ "links" ] ) as $entry ) {
		$xml_document .= "\n<ref id=\"show.php?wikipage_id=$entry\">" . $wikipage[ "links" ][ $entry ] . "</ref>";
	}
	$xml_document .= "\n";

	$xml_document .= <<<END
<kwd1>{$wikipage[ "keyword1" ]}</kwd1>
<kwd2>{$wikipage[ "keyword2" ]}</kwd2>
<kwd3>{$wikipage[ "keyword3" ]}</kwd3>
<author>{$wikipage[ "author" ]}</author>
<tit>{$wikipage[ "title" ]}</tit>
<bdy>{$wikipage[ "content" ]}</bdy>
<rawbdy>{$wikipage[ "raw_content" ]}</rawbdy>
</page>
END;

	return $xml_document;
}

?>
