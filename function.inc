<?php

include_once( "config.php.inc" );

/*
* Connect in the SGBD and selects the CoTeia's database.
*/
function coteia_connect()
{
	global $dbhost, $dbuser, $dbpass, $dbname;

	$db_handler = mysql_pconnect( $dbhost, $dbuser, $dbpass );
	if ( !$db_handler ) {
		show_error( 1 );
	}

	$ok = mysql_select_db( $dbname, $db_handler );
	if ( !$ok ) {
		show_error( 1 );
	}

	return $db_handler;
}

/*
* Authenticate a user into a given swiki.
* Wikipage 0 (zero) is special, is the admin "wikipage".
*/
function coteia_login( $username, $password, $wikipage )
{
	$db_handler = coteia_connect();
	@session_start();

	// Authenticate the user (if needed).
	if ( ! $_SESSION[ "authenticated" ] ) {
		$auth_query  = "select id from users where username='$username' and password=md5('$password')";
		$auth_result = mysql_query( $auth_query );
		if ( mysql_num_rows( $auth_result ) == 0 ) {
			return false;
		}
		$auth_tuple = mysql_fetch_array( $auth_result );
		$_SESSION[ "authenticated" ] = true;
		$_SESSION[ "user_id" ] = $tuple[ "id" ];
		mysql_free_result( $auth_result );
	}

	$role_query = "select role from roles where userid='" . $_SESSION[ "user_id" ] . "' and wikipage='$wikipage'";
	$role_result = mysql_query( $role_query );
	// If no specific role was assigned, enter as "guest".
	if ( mysql_num_rows( $role_result ) == 0 ) {
		$_SESSION[ "role" ] = "guest";
	} else {
		$role_tuple = mysql_fetch_array( $role_result );
		$_SESSION[ "role" ] = $role_tuple[ "role" ];
	}
	mysql_free_result( $role_result );

	return true;
}

/*
* Logout an user from CoTeia.
*/
function coteia_logout()
{
	session_commit();
	session_unset();
	session_destroy();
}

/**
* Check if the character data inside an XML element can be replaced by HTML entities.
* The default is "true".
*/
function can_replace_chars_within( $xml_element ) {
	$result = true;
  if ( preg_match( "/^(.*:)?pre.*/i", $xml_element ) > 0 ) {
		$result = false;
	}
	return $result;
}

/*
* Replace special chars by their respective HTML entity.
*/
function replace_chars( $text ) {
  $inside_tag = false;
	$elements = array();
	$current_element = "";
	$save_element_name = false;

	$new_text = "";
  for ( $i = 0; $i < strlen( $text ); $i++ ) {

    // Detects the possible start of a tag.
    if ( $inside_tag == false && $text{ $i } == "<" ) {
      // Check if it's a element's start tag or tag of empty element or if it's an end tag:
			// * It'll be an start tag or empty element's tag  if the "<" is not followed by a blank
			//   space (as defined by the XML standard).
			// * It'll be and end tag if the "<" if followed by an "/".
			// Anything different from that is a invallid element definition, will be catched by
			// the XML parser.
      if ( strlen( trim( $text{ $i + 1 } ) ) || $text{ $i + 1 } == "/" ) {
        $inside_tag = true;
				$new_text = $new_text . $text{ $i };

				// Check if it's a start or empty element, if yes check to save it's name.
				if ( strlen( trim( $text{ $i + 1 } ) ) {
					$save_element_name = true;
					array_push( $elements, "" );
				} else {
					// We're in a close tag, so clean the current_element:
					$current_element = "";
				}
			}
			// No futher check, go to the next char.
			continue;
    }

    // Detects the possible end of a tag.
    if ( $inside_tag == true }
			if ( $text{ $i } == ">" ) {
	      $inside_tag = false;
				$new_text = $new_text . $text{ $i };

				// If it was an end tag (so, with save_element_name as false), pop the element's name.
				if ( $save_element_name ) {
					$save_element_name = false;
					$current_element = array_pop( $elements );
				}
			} else {
				// We're inside a tag yet, let's keep saving the element's name and attributes.
				if ( $save_element_name ) {
					$element = array_pop( $elements );
					$array_push( $elements, $element . $text{ $i } );
				}
			}
			continue;
		}

    // Replace chars.
    if ( ! $inside_tag && can_replace_chars_within( $current_element ) ) {
      $new_text = $new_text . htmlspecialchars( $text{ $i } );
    } else {
			$new_text = $new_text . $text{ $i };
		}
  }
  return $new_text;
}

/*
* Replace special chars by their respective HTML entity.
*/
function replace_html_entities( $text ) {
  $inside_tag = false;

  $new_text = "";
  $cdata = "";
  for ( $i = 0; $i < strlen( $text ); $i++ ) {
    // Detects the possible start of a tag.
    if ( $inside_tag == false && $text[ $i ] == "<" ) {
      // Check if it's a element's start tag or tag of empty element or if it's an end tag.
      if ( strlen( trim( $text{ $i + 1 } ) ) || $text{ $i + 1 } == "/" ) {
        $inside_tag = true;
        $new_text = $new_text . html_entity_decode( $cdata ) . $text{ $i };
        $cdata = "";
        continue;
      }
    }
    // Detects the possible end of a tag.
    if ( $inside_tag == true && $text{ $i } == ">" ) {
      $inside_tag = false;
      $new_text = $new_text . $text{ $i };
      continue;
    }

    // Replace chars.
    if ( ! $inside_tag ) {
      $cdata = $cdata . $text{ $i };
    } else {
      $new_text = $new_text . $text{ $i };
    }
  }
  $new_text = $new_text . html_entity_decode( $cdata );
  return $new_text;
}

/**
* Prepare the data for database's processing.
*/
function prepare_for_db( $index, $content, $title, $author, $keyword ) {
	// Strip slashes if magic_quotes_gpc (auto adding slashes) is active.
	if ( get_magic_quotes_gpc() == 1 ) {
		$index = stripslashes( $index );
		$content = stripslashes( $content );
		$title = stripslashes( $title );
		$author = stripslashes( $author );
		for ( $i = 0; $i < count( $keyword ); $i++ ) {
			$keyword[ $i ] = stripslashes( $keyword[ $i ] );
		}
	}

	$data[ "index" ] = mysql_escape_string( trim( $index ) );
  $data[ "content" ] = mysql_escape_string( trim( $content ) );
  $data[ "title" ] = mysql_escape_string( trim( $title ) );
  $data[ "author" ] = mysql_escape_string( trim( $author ) );
  for ( $i = 0; $i < count( $keyword ); $i++ ) {
    $data[ "keyword" . $i ] = mysql_escape_string( trim( $keyword[ $i ] ) );
  }

	return $data;
}

/**
* Prepare the data for web.
*/
function prepare_for_web( $index, $content, $title, $author, $keyword ) {
	// Strip slashes if magic_quotes_gpc (auto adding slashes) is active.
	if ( get_magic_quotes_gpc() == 1 ) {
		$index = stripslashes( $index );
		$content = stripslashes( $content );
		$title = stripslashes( $title );
		$author = stripslashes( $author );
		for ( $i = 0; $i < count( $keyword ); $i++ ) {
			$keyword[ $i ] = stripslashes( $keyword[ $i ] );
		}
	}

	$data[ "index" ] = replace_chars( trim( $index ) );
	$data[ "title" ] = replace_chars( trim( $title ) );
	$data[ "author" ] = replace_chars( trim( $author ) );
	$data[ "content" ] = replace_chars( trim( $content ) );
	for ( $i = 0; $i < count( $keyword ); $i++ ) {
		$data[ "keyword" . $i ] = replace_chars( trim( $keyword[ $i ] ) );
	}

	return $data;
}

/*
* Execute the XML processing via XSLT and save the data.
*/
function process_xml_document( $id, $xml_document, $path_xml, $path_xsl, $path_html )
{
	global $PATH_JAVA, $PATH_XT;

	$arq_xml = "$path_xml/$id.xml";
	if ( is_file( $arq_xml ) ) {
		chmod( $arq_xml, 0700 );
	}
	$fp = fopen( $arq_xml , "w+" );
	fputs( $fp, $xml_document );
	fclose( $fp );

	$cmd_line = "$PATH_JAVA org.apache.xalan.xslt.Process -in $arq_xml -xsl $path_xsl -out $path_html/$id.html 2>&1";
	exec( $cmd_line, $var_array, $status );
	if ( $status == 0 ) {
		return true;
	} else {
		return $var_array;
	}
}

/*
* Prepare XML document for processing.
*/
function prepare_xml_document( $wikipage, $path_xml, $path_dtd, $path_xsl, $path_html )
{
	$xml_document .=  <<<END
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE coteia SYSTEM "coteia.dtd">
<page>
<id>{$wikipage[ "ident" ]}</id>
<lock>{$wikipage[ "lock" ]}</lock>
<ann_folder>{$wikipage[ "annotation" ]}</ann_folder>
<chat_folder>{$wikipage[ "chat" ]}</chat_folder>
<id_eclass>{$wikipage[ "eclass" ]}</id_eclass>
<sw_id>{$wikipage[ "id_swiki" ]}</sw_id>"
{$wikipage_web[ "links" ]}
<keyword1>{$wikipage[ "keyword1" ]}</keyword1>
<keyword2>{$wikipage[ "keyword2" ]}</keyword2>
<keyword3>{$wikipage[ "keyword3" ]}</keyword3>
<author>{$wikipage[ "author" ]}</author>
<title>{$wikipage[ "title" ]}</title>
<content>{$wikipage[ "content" ]}</content>
</page>
END;

	return process_xml_document( $wikipage[ "ident" ], $xml_document, $path_xml, $path_xsl, $path_html );
}

/*
* Create the links from a wikipage.
*
* If id=$ident.x and titulo=tupla[titulo], create a link  /c.c. [create].
*/
function prepare_links( $id, $conteudo, $dbh ) {
	$n = "0";
	$continua = true;
	$texto = "" . $conteudo;
	$open_tag = "<lnk>";
	$close_tag = "</lnk>";
                        
	while ( $continua == true ) {
		$texto_pos_ini = strpos( $texto, $open_tag );
		if ( !$texto_pos_ini ) {
			$continua = false;
		} else {
			$texto_pos_fin = strpos( $texto, $close_tag );
			$texto_ini = $texto_pos_ini + strlen( $open_tag) + 1;
			$texto_tam = $texto_pos_fin - $texto_ini;
			$partes_texto[ $n ] = substr( $texto, $texto_ini, $texto_tam ); //proprio link
			$ini_resto_texto = $texto_pos_fin + strlen( $close_tag ) + 1;
			$texto = substr( $texto, $ini_resto_texto );

			//encontra id_swiki
			$get_swiki = explode( ".", $id );
			$swiki = $get_swiki[ 0 ];

			//encontra paginas dentro de uma mesma swiki
			$query_comp = "select indexador,ident from paginas  where ((ident like '$swiki.%')  or (ident='$swiki'))";
			$result_comp = mysql_query( $query_comp, $dbh );
			while ( $tupla = mysql_fetch_array( $result_comp ) ) {
				//compara link do textarea com uma pagina do banco de dados
				if ( !strcmp( trim( $partes_texto[ $n ] ), $tupla[ "indexador" ] ) ) {
					// Procura por identificador da pagina encontrada
					$indexador = mysql_escape_string( $tupla[ "indexador" ] );
					$query_find =  "select ident,data_ultversao from paginas where indexador='$indexador' and (ident like '$swiki.%'  or ident='$swiki')";
					$result_find = mysql_query( $query_find, $dbh );
					$tupla_find = mysql_fetch_array( $result_find );
					$pagina = $tupla_find[ "ident" ];

					// Acerta o formato da data
					$datetime = explode( " ", $tupla_find[ "data_ultversao" ] );
					$date = explode( "-", $datetime[ 0 ] );
					$data_formato_correto = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $datetime[1];

					// Já foi encontrada a tupla, pode sair direto do while.
					break;
				}
			}

			// Encontra a posicao correta na variavel, mesmo depois de inserir a linkagem.
			$texto_pos_inicio = strpos( $conteudo, $open_tag . $partes_texto[ $n ] . $close_tag );
			$texto_inicio = $texto_pos_inicio + strlen( $open_tag) + 1;
			$texto_pos_final = $texto_inicio + $texto_tam;

			if ( $tupla != false ) {
				$cont1 = substr_replace( $conteudo, "</a>", $texto_pos_final, 0 );
				$cont2 = substr_replace( $cont1, '<a href="show.php?ident=' . $pagina . ' onMouseOver="window.status=\'Última versão: ' . $data_formato_correto . '\'; return true">', $texto_inicio, 0 );
				$texto_final = $cont2;
			} else {
				$num = 1;

				// Procura por novo indice
				$query_search = "select count(*) as CONTADOR from paginas where ((ident like '$id.%') and (ident not like '$id.%.%')) ORDER by ident asc";
				$result_search = mysql_query( $query_search, $dbh );
				$tupla_cont = mysql_fetch_array( $result_search );
				$nro_pages = $tupla_cont[ "CONTADOR" ];

				$num = $nro_pages + 1;
				//indice de novas paginas
				$partes_texto[ $n ] = rawurlencode( $partes_texto[ $n ] );
				$cont1 = substr_replace( $conteudo, "[create]</a>", $texto_pos_final, 0 );
				$cont2 = substr_replace( $cont1, '<a href="create.php?ident=' . $id . '.' . $num . '&index=' . $partes_texto[$n] . '">', $texto_inicio + $texto_tam, 0 );
				$texto_final = $cont2;
			}
			$n++; //incrementa a variavel de links
			$conteudo = $texto_final;
		}
	}
	return trim( $texto_final );
}


/**
* Extract the swiki identificator from a full wikipage identificator.
*/
function extract_parent_wikipage( $ident ) {
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
*/
function extract_swiki_id( $ident ) {
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	}

	if ( strpos( $ident, "." ) ) {
		$get_swiki = explode( ".", $ident );
    $id_swiki = $get_swiki[ 0 ];
  } else {
    $id_swiki = $ident;
  }

	return $id_swiki;
} 

/**
* Check if a wikipage identificator is valid.
*
* id ::= [0-9]+ ( "." [0-9] )*
*/
function check_wikipage_id( $ident ) {
	return ( preg_match( "/^[\d]+(\.([\d]+))*\$/", $ident ) > 0 ) ? true : false;
}

/**
* Check if a string matches the start tag for the given element.
*/
function match_start_tag( $content, $type ) {
	return ( preg_match( "/.*<$type\s.*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if a string matches the end tag for the given element type.
*/
function match_end_tag( $content, $type ) {
	return ( preg_match( "/.*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if a string matches the tag for the given empty element type.
*/
function match_empty_tag( $content, $type ) {
	return ( preg_match( "/.*<$type\s*\/>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if the content holds a element type with the specified character data.
*/
function match_tag( $content, $type, $cdata ) {
	return ( preg_match( "/.*<$type\s.*>\s*$cdata\s*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Handle errors found.
*/
function show_error( $error_code, $message ) {
  include( "err.inc" );
}

?>
