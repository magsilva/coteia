<?php

include_once( "config.php.inc" );

/*
 * NOME:	        update_cvs
 * DESCRICAO:		-- 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function update_cvs($id, $ModuloCheckout) {
	global $PATH_ARQUIVOS, $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME;

	//arquivo a ser feito checkout
	$arquivo = $id . ".html";

	//caminho completo do arquivo a ser feito checkout
	$path_arquivo = $PATH_ARQUIVOS . "/" . $arquivo;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . "/" . $CVS_REPOSITORY;

	// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
	// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
	// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
	// Posteriormente ele deve ser excluido.
	$caminho = cria_dir( "coteia" );
	chdir( $caminho );

	$comando_checkout = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " checkout " . $ModuloCheckout;
	exec($comando_checkout);

	$caminho_checkout = $caminho . "/" . $ModuloCheckout;
	chdir( $caminho );

	//coloca o arquivo salvo (ou do qual foi feito um upload) dentro do diretorio de trabalho
	copy( $path_arquivo, $caminho_checkout );

        // Faz commit do arquivo
        exec( "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " commit -m \"\" " . $arquivo );

	apaga_dir($caminho);

	return true;
}

/*
 */
function add_cvs( $ident, $ModuloCheckout ) {
	global $PATH_ARQUIVOS, $CVS_HOST, $CVS_REPOSITORY, $CVS_USERNAME;

	//arquivo a ser feito checkout
	$arquivo = $ident . ".html";

	//caminho completo do arquivo a ser feito checkout
	$path_arquivo = $PATH_ARQUIVOS . "/" . $arquivo;

	$cvs_root = ":pserver:" . $CVS_USERNAME . "@" . $CVS_HOST . "/" .  $CVS_REPOSITORY;

	// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
	// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
	// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
	// Posteriormente ele deve ser excluido.
	$caminho = cria_dir( "coteia" );
	chdir( $caminho );

	$comando_checkout = "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " checkout " . $ModuloCheckout;
	exec( $comando_checkout );

	$caminho_checkout = $caminho . "/" . $ModuloCheckout;
	chdir( $caminho );
	// Coloca o arquivo salvo dentro do diretorio de trabalho.
	copy( $path_arquivo, $caminho_checkout  );

	// Adiciona o arquivo ao repositorio
	exec( "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " add " . $arquivo );

	// Faz commit do arquivo
	exec( "CVS_PASSFILE=" . $CVS_PASSFILE . " cvs -d " . $cvs_root . " commit -m \"\" " . $arquivo );
 
	apaga_dir($caminho);

	return true;
}

/*
 * NOME:	        cria_dir
 * DESCRICAO:		-- 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function cria_dir($diretorio) {
	$nomedir = tempnam($diretorio, "cvs");
	exec("rm -rf $nomedir");
	mkdir($nomedir,0700);
	return ($nomedir);
}

/*
 * NOME:	        apaga_dir
 * DESCRICAO:		-- 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function apaga_dir($nomearq) {
	exec ("rm -rf $nomearq");
	return true;
}

?>
