<?php

include_once( "../config.php.inc" );
include_once( "cvs_util.php.inc" );

function recursive_chmod( $path2dir, $mode ) {
	$dir = dir( $path2dir );
	while( ( $file = $dir->read() ) !== false ) {
		$full_file = $dir->path . "/" . $file;
		chmod( $full_file, $mode );
		if ( is_dir( $full_file ) ) {
			if ( $file != "." && $file != ".." ) {
				recursive_chmod( $full_file, $mode );
			}
		} 
	}
	$dir->close();
}

function replace_vars( $squema ) {
	extract($GLOBALS);

	$sql_squema = "\$translated_squema = <<<END\n";
	$sql_squema .= file_get_contents( $squema );
	$sql_squema .= "END;\n";
	eval( $sql_squema );

	$sql_squema_file = fopen( str_replace( ".raw", "" , $squema ), "w" );
	fwrite( $sql_squema_file, $translated_squema );
	fclose( $sql_squema_file );
}


function setup_dir( $dir ) {
	if ( !file_exists( $dir ) ) {
		mkdir( $dir, 0777 );
	}
	chmod( $dir, 0777 );
	recursive_chmod( $dir, 0777 );
}

function login_cvs() {
  global $PATH_COWEB, $CVS_USERNAME, $CVS_HOST, $CVS_REPOSITORY, $CVS_PASSWORD, $CVS_PASSFILE;

  $pass_file = fopen( $CVS_PASSFILE, "w" );
  fwrite( $pass_file, ":pserver:".$CVS_USERNAME."@".$CVS_HOST.":".$CVS_REPOSITORY );
  fwrite( $pass_file, " " );
  fwrite( $pass_file, scramble( $CVS_PASSWORD ) );
  fclose( $pass_file );
}

echo "\nSettings directories permissions...";
setup_dir( $PATH_XML );
setup_dir( $PATH_XHTML );
setup_dir( $PATH_UPLOAD );
setup_dir( $PATH_ARQUIVOS );
echo "Ok";

echo "\nSetting files permissions...";
if ( !file_exists( $PATH_COWEB . "/log.txt" ) ) {
	touch( $PATH_COWEB . "/log.txt" );
}
echo "Ok";

echo "\nCreating CVS password...";
login_cvs();
echo "Ok";

echo "\nCreating the database schemas and XSLT stylesheet...";
foreach (glob("*.raw") as $raw_squema) {
	replace_vars( $raw_squema );
}
echo "Ok";

@copy( "coteia.xsl", "../coteia.xsl" );


echo "\nFinished.\n";

?>
