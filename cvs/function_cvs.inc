<?php

include_once( "config.php.inc" );

/**
* Check if the repository has been checked out. If not, do it now.
*/
function cvs_check( $module ) {
	global $CVS_CHECKOUT_DIR;

	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	if ( !is_file( $CVS_CHECKOUT_DIR . "/" . $module . "/CVS" ) ) {
		cvs_checkout_module( $module );
	}
		
	chdir( $old_dir );
	return true;
}

/*
* Update a file.
*/
function cvs_update( $id, $module ) {
	global $PATH_ARQUIVOS, $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	// Arquivo a ser feito checkout.
	$arquivo = $PATH_ARQUIVOS . "/" . $id . ".html";

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();

	cvs_check( $module );
	chdir( $CVS_CHECKOUT_DIR . "/" . $module );

	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " update " . basename( $arquivo );
	exec( $cvs_cmdline );

	// Adiciona o arquivo ao repositório.
	copy( $arquivo, $CVS_CHECKOUT_DIR . "/" . $module . "/" . basename( $arquivo ) );

	// Faz commit do arquivo no repositório.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " commit -m \"\" " . basename( $arquivo );
	exec( $cvs_cmdline );

	chdir( $old_dir );
	return true;
}

/*
* Add a new file to the CVS repository.
*/
function cvs_add( $id, $module ) {
	global $PATH_ARQUIVOS, $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	// Arquivo a ser feito checkout.
	$arquivo = $PATH_ARQUIVOS . "/" . $id . ".html";

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();

	cvs_check( $module );
	chdir( $CVS_CHECKOUT_DIR . "/" . $module );

	// Adiciona o arquivo ao repositório.
	copy( $arquivo, $CVS_CHECKOUT_DIR . "/" . $module . "/" . basename( $arquivo ) );
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " add " . basename( $arquivo );
	exec( $cvs_cmdline );

	// Faz commit do arquivo no repositório.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " commit -m \"\" " . basename( $arquivo );
	exec( $cvs_cmdline );

	chdir( $old_dir );

	return true;
}

/*
* Checkout the entire code.
*/
function cvs_checkout_module( $module ) {
	global $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	// Normalmente isto seria desnecessário, o reposítorio não deveria ter tal arquivo.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " checkout " . $module;
	exec( $cvs_cmdline );

	chdir( $old_dir );
	return true;
}

/**
* Update the entire code.
*/
function cvs_update_module( $module ) {
	global $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	// Normalmente isto seria desnecessário, o reposítorio não deveria ter tal arquivo.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " update " . $module;
	exec( $cvs_cmdline );

	chdir( $old_dir );
	return true;
}

/**
* Get the content of a given file's release number.
*/
function cvs_checkout_file( $file, $release ) {
	global $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	// Normalmente isto seria desnecessário, o reposítorio não deveria ter tal arquivo.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " checkout -p -l -r " . $release . " " . $file;
	$pipe = popen( $cvs_cmdline, "r" );
	while( $buffer = fgets( $pipe, 1024 ) ) {
        $content .= $buffer; 
	}
	pclose( $pipe );

	chdir( $old_dir );
	return $content;
}

/**
* Return a array with the versions of the given file.
*/
function cvs_get_revisions( $file ) {
	global $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME, $CVS_CHECKOUT_DIR, $CVS_PASSFILE;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . ":" . $CVS_REPOSITORY;
	$cvs_cmdline = "";
	$old_dir = getcwd();
	chdir( $CVS_CHECKOUT_DIR );

	// Normalmente isto seria desnecessário, o reposítorio não deveria ter tal arquivo.
	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " log -l " . $file . "| grep ^revision | cut -f 2 -d ' '";
	exec( $cvs_cmdline, $revisions );

	$cvs_cmdline = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " log -l " . $file . "| grep ^date | cut -f 2-3 -d ' '";
	exec( $cvs_cmdline, $dates );

	$output = array();
	for ( $i = 0; $i < count( $revisions ); $i++ ) {
    $dates[ $i ] = str_replace( ";", "", $dates[ $i ] );
		$output[ $revisions[ $i ] ] = $dates[ $i ];
	}

	chdir( $old_dir );
	return $output;
}

?>
