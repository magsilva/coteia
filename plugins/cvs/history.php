<?php
/**
* Show the visible swikis and some usage statistics.
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( dirname(__FILE__) . "/../../function.php.inc" );
include_once( dirname(__FILE__) . "/cvs-api.php.inc" );

/**
* Discover (set) the action.
*/
if ( isset( $_REQUEST[ "revision" ] ) ) {
	$action = "retrieve";
} else {
	$action = "list";
}


/**
* Checking parameters.
*/
if ( ! isset( $_REQUEST[ "wikipage_id" ] ) ) {
  show_error( _( "Missing parameter: 'wikipage_id'" ), 0 );
}
$wikipage_id = extract_wikipage_id( $_REQUEST[ "wikipage_id" ] );
if ( $wikipage_id === false ) {
  show_error( _( "The parameter 'wikipage_id' is invalid." ) );
}

$module = $CVS_WIKIPAGE_MODULE;

if ( $action == "retrieve" ) {
	$filename = $CVS_MODULE . "/" . $wikipage_id . ".html";
	$revision = cvs_has_revision( $module, $filename, $_REQUEST[ "revision" ] );
	if ( $revision === false ) {
		show_error( _( "The parameter 'revision' is invalid" ) );
	}
	if ( isset( $_REQUEST[ "compare" ] ) ) {
		$compare = $_REQUEST[ "compare" ];
	} else {
		$compare = 0;
	}
}


// Common header
echo get_header( _( "Wikipage history" ) );
echo "\n</head>";
echo "\n\n<body>\n\n";
include( dirname(__FILE__) . "/../../toolbar.php.inc" );


/**
* Execute action.
*/
if ( $action == "retrieve" ) {
	if ( $compare ) {
		$content1 = cvs_checkout_revision( $module, $filename, "HEAD" );
		$content1 = preg_replace( "'<html>.*?<h2>'si", "<h2>", $content1, 1 );
		$content1 = eregi_replace( "</body>", "", $content1 );
		$content1 = eregi_replace( "</html>", "", $content1 );
	}
	$content2 = cvs_checkout_revision( $module, $filename, $revision );
	$content2 = preg_replace( "'<html>.*?<h2>'si", "<h2>", $content2, 1 );
	$content2 = eregi_replace( "</body>", "", $content2 );
	$content2 = eregi_replace( "</html>", "", $content2 );

	/**
	* Show side by side with current (latest) version.
	*/
	if ( $compare ) {
		echo "\n<div class=\"source1\">";
		echo "\n<p><strong>" .  _( "Current revision ") . "</strong></p>\n";
		echo $content1;
		echo "\n</div>\n";	
	}

	if ( $compare ) {
		echo "\n<div class=\"source2\">";
	} else {
		echo "\n<div class=\"source2\" style=\"width: 100%\">";
	}
	echo "\n<p><strong>" . _( "Revision ") . $revision . "</strong></p>\n";
	echo $content2;
	echo "\n</div>\n";
}


if ( $action == "list" ) {
	/**
	* Retrieving known revisions for the wikipage.
	*/
	$revisions = cvs_get_revisions( $module, wikipage2cvs( $wikipage_id ) );
?>

<br />
<form method="get" name="history" action="history.php">
	<?php echo _( "Revision (version) to be retrieved" ); ?>
	<br />
	<select name="revision">
	<?php
		foreach ( array_keys( $revisions ) as $revision ) {
			echo "\t<option value=\"$revision\">";
			echo $revisions[ $revision ];
			echo "</option>\n";
		}
	?>
	</select>
	<input type="submit" value="Retrieve" />
	<br />
	<br />
	<input type="checkbox" name="compare" value="1" checked /><?php echo _( "Show side by side with current version" ); ?>
	<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_id;?>" />

</form>

<?php
}
?>

</body>

</html>
