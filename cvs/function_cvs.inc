<?php

/****************************************************************************
 * Variaveis Globais                                                        *
 ****************************************************************************/

//caminho completo do arquivo 
$PATH_FILES = "/var/www/html/coweb/files/";
//IP do servidor
$host = "";
//caminho do repositorio no servidor
$caminho_repositorio = "";
//username no repositorio
$username = "";
//caminho dos arquivos para checkout
$parent = "/coteia/";

/****************************************************************************
 * Funcoes                                                                  *
 ****************************************************************************/
/*
 * NOME:	        update_cvs
 * DESCRICAO:		-- 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function update_cvs($id,$ModuloCheckout) {

global $PATH_FILES, $host, $caminho_repositorio, $username, $parent;

//caminho completo do arquivo a ser feito checkout
$path_arquivo = $PATH_FILES.$id.".html";

//arquivo a ser feito checkout
$arquivo = $id.".html";

// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
// Posteriormente ele deve ser excluido.

$caminho = cria_dir($parent);
$caminho .= "/";
chdir($caminho);

$comando_checkout = "cvs -d :pserver:".$username."@".$host.":".$caminho_repositorio." co ".$ModuloCheckout;
exec($comando_checkout);

$caminho_checkout = $caminho.$ModuloCheckout;

chdir($caminho_checkout);

//observacao: esses comandos tambem podem ser executados com popen
//coloca o arquivo salvo (ou do qual foi feito um upload) dentro do diretorio de trabalho

exec("cp ".$path_arquivo." ".$caminho_checkout);

//faz commit do arquivo
exec("cvs -d :pserver:".$username."@".$host.":".$caminho_repositorio." commit -m \"\" ".$arquivo);

apaga_dir($caminho);

return true;

}

/*
 * NOME:	        add_cvs
 * DESCRICAO:		-- 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function add_cvs($ident,$ModuloCheckout) {

global $PATH_FILES, $host, $caminho_repositorio, $username, $parent;

//caminho completo do arquivo a ser feito checkout
$path_arquivo = $PATH_FILES.$ident.".html";

//arquivo a ser feito checkout
$arquivo = $ident.".html";

// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
// Posteriormente ele deve ser excluido.

$caminho = cria_dir($parent);
$caminho .= "/";
chdir($caminho);

$comando_checkout = "cvs -d :pserver:".$username."@".$host.$caminho_repositorio." co ".$ModuloCheckout;
exec($comando_checkout);

$caminho_checkout = $caminho.$ModuloCheckout;

chdir($caminho_checkout);

//observacao: esses comandos tambem podem ser executados com popen
//coloca o arquivo salvo (ou do qual foi feito um upload) dentro do diretorio de trabalho

exec("cp ".$path_arquivo." ".$caminho_checkout);

//adiciona o arquivo ao repositorio
exec("cvs -d :pserver:".$username."@".$host.$caminho_repositorio." add -kb ".$arquivo);

//faz commit do arquivo
exec("cvs -d :pserver:".$username."@".$host.$caminho_repositorio." commit -m \"\" ".$arquivo);
 
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
