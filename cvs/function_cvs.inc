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

?>
