<?php
/**
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* This code is licenced under the GNU General Public License (GPL).
*/
?>

<?php
/**
* Update the parent-page (actually, any relative wikipage).
*/
require_once( "function.inc" );

$dbh = db_connect();
mysql_select_db( $dbname, $dbh );

$parent_related_query = "select * from paginas where ident='$parent_id'";  
$parent_related_result = mysql_query( $query, $dbh );
$parent_tuple = mysql_fetch_array( $parent_related_result );

$parent_swiki_id = extract_swiki_id( $parent_id );
if ( $parent_swiki_id == false ) {
	$st = 0;
	include( "err.inc" );
}

$parent_index = $parent_tuple[ "index" ];
$parent_title = $parent_tuple[ "title" ];
$parent_content = $parent_tuple[ "content" ];
$parent_author = $parent_tuple[ "author" ];
$parent_keywords = $parent_tuple[ "kwd1" ];

// We need these vars sometimes, so let's keep it ready for using.
$parent_index_db = mysql_escape_string( $parent_index );

// Prepare data for xml processing.
$parent_wikipage = prepare_for_web( $parent_index, $parent_content, $parent_title, $parent_author, $parent_keywords );
$parent_wikipage[ "id_swiki" ] = $parent_swiki_id;
$parent_wikipage[ "ident" ] = $parent_id;

// Prepare annotations.
if ( match_empty_tag( $parent_wikipage[ "content" ], "note" ) ) {
	$parent_wikipage[ "content" ] = note( $parent_wikipage[ "content" ] ) ;
}

// Prepare wikilinks.
if ( match_start_tag( $parent_wikipage[ "content" ], "lnk" ) ) {
	$parent_wikipage[ "content" ] = link_interno( $parent_wikipage[ "ident" ], $parent_wikipage[ "content" ], $dbh );
}

// Prepare backtrack references.
if ( strpos( $parent_swiki_id, "." ) != false ) {
	// If the wikipage isn't the swiki's main page.
	$i = 0;
	$parent_bt_query = "select ident,titulo from paginas where ( ident like '$parent_swiki_id.%' or ident='$parent_swiki_id' ) and conteudo like '%$parent_index_db%'";
	$parent_bt_result = mysql_query( $parent_bt_query, $dbh );
	while ( $parent_bt_tuple = mysql_fetch_array( $parent_bt_result ) ) {
		if ( match_tag( $parent_bt_tuple[ "titulo" ], "lnk", $parent_index_db ) ) {
			$parent_linksto_id[$i] = $parent_bt_tuple[ "ident" ];
			$parent_linksto_title[$i] = $parent_bt_tuple[ "titulo" ];
			$i++;
		}
	}
	mysql_free_result( $parent_bt_result );
} else {
	// Else the wikipage is the swiki's main page.
	$parent_linksto_id[0] = "0";
	$parent_linksto_title[0] = "Lista de Swikis";
}

$parent_links = "";
$i = 0;
if ( $parent_linksto_id[0] == "0" ) {
	$parent_links = $parent_links . "<ref id=\"index.php\">$parent_linksto_title[0]</ref>\n";
	$i++;
}
for ( ; $i < count( $parent_linksto_id ); $i++ ) {
	$parent_links = $parent_links . "<ref id=\"mostra.php?ident=$parent_linksto_id[$i]\">$parent_linksto_title[$i]</ref>\n";
}
$parent_wikipage[ "links" ] = $parent_links;

// Augment page with data for CoTeia's third-party components.
$parent_components_query = "select id_ann, id_chat, id_eclass from swiki where id='$parent_swiki_id'";
$parent_components_result = mysql_query( $parent_components_query, $dbh );
$parent_components_tuple = mysql_fetch_array( $parent_components_result );
$parent_wikipage[ "annotation "] = $parent_components_tuple[ "id_ann"];
$parent_wikipage[ "chat" ]  = $parent_components_tuple[ "id_chat" ];
$parent_wikipage[ "eclass" ] = $parent_components_tuple[ "id_eclass" ];
mysql_free_result( $parent_components_result );

// Variables needed for XML transformation.
$path_xml = $PATH_XML;
$arq_xsl = $PATH_XSL;
$path_html = $PATH_XHTML;

// The XML transformation.
$parent_result = xml_xsl( $parent_wikipage, $path_xml, $path_dtd, $path_xsl, $path_html );

// Check if everything has gone ok.
if ( is_bool( $parent_result ) && $parent_result == TRUE ) {
	cvs_update( $parent_ident, $CVS_MODULE );
} else {
	// The XML transformation didn't work.
	$st = 2;
	include( "err.inc" );
}
mysql_free_result( $parent_related_result );

?>
