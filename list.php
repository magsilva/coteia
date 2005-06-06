<?php
/**
* Show wikipages.
*
* Show the requested wikipage, compiling an updated version
* if outdated.
*
* @param wikipage_id [string] Identifier of a wikipage. If the wikipage_id
* is set to zero, then it's a new page that's being created. In this case,
* the parameter 'index' and 'swiki_id' must be set too.
* @param swiki_id [string] Identifier of a swiki (must be set only when
* creating a wikipage).
* @param index [string] Name of a wikipage (must be set only when creating
* a wikipage).
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

/**
* Checking parameters.
*/

if ( ! isset( $_REQUEST[ "swiki_id" ] ) ) {
	show_error( _( "Missing parameter: 'swiki_id'" ), 0 );
}

$swiki_id = $_REQUEST[ "swiki_id" ];
if ( check_swiki_id( $swiki_id ) === false ) {
	show_error( _( "The parameter 'swiki_id' is invalid." ) );
}
// The root swiki (the application swiki) is not a valid option.
if ( $swiki_id == 0 ) {
	show_error( _( "The parameter 'swiki_id' is invalid." ) );
}

$format = "html";
if ( isset( $_REQUEST[ "format" ] ) ) {
    $format = basename( $_REQUEST[ "format" ] );
}



// Find the swiki the wikipage belongs to
coteia_connect();
$query = "select status from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	show_error( _( "The swiki the wikipage supposedly belongs to couldn't be found." ) );
}
$tuple = mysql_fetch_array( $result );
$status = $tuple[ "status" ];
mysql_free_result( $result );


// Check if the user is allowed to access the requested swiki and redirect to login
// if required.
if ( $status == "1" ) {
	session_name( "coteia" );
	session_start();
	if ( ! isset( $_SESSION[ "swiki_" . $swiki_id ] ) ) {
		$url = "list.php?swiki_id=$swiki_id";
		header( "Location: $url" );
		exit();
  }
}


$query = "select titulo from swiki where id=$swiki_id";
$result = mysql_query( $query );
$tuple = mysql_fetch_array( $result );
$swiki_title = $tuple[ "titulo" ];
mysql_free_result( $result );

$query = "select ident,titulo from paginas where ( ident='$swiki_id' or ident like '$swiki_id.%') order by titulo";
$result = mysql_query( $query  );
$wikipages = array();
while ( $tuple = mysql_fetch_array( $result ) ) {
	$wikipages[] = $tuple;	
}
mysql_free_result( $result );

if ( $format == "csv" ) {
	foreach ( $wikipages as $wikipage ) {
		echo $wikipage[ "ident" ] . "," . $wikipage[ "titulo" ] . "\n";
	}
} else if ( $format == "html" ) {

echo get_header( _( "$swiki_title index" ) );
?>

<body>

<?php
include( "toolbar.php.inc" );
?>

<h2><?php echo $swiki_title ?></h2>

<ul>
<?php
foreach ( $wikipages as $wikipage ) {
	echo "\n\t<li><a href=\"show.php?wikipage_id=" . $wikipage[ "ident" ] . "\">" .  $wikipage[ "titulo" ] . "</a></li>";
}
?>
</ul>

</body>

</html>

<?php
} else {
	show_error( _( "Output format not supported" ) );
}
?>
