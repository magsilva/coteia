<?php

/****************************************************************************
 * Variaveis Globais                                                        *
 ****************************************************************************/

// especificacoes da base de dados
$dbhost = "localhost";
$dbname = "cowebBD";
$dbuser = "coweb";
$dbpass = "coweb";

// especificacoes da base de dados do chat
$cs_dbhost = "catuaba.icmc.sc.usp.br";
$cs_dbname = "chatserver";
$cs_dbuser = "cs";
$cs_dbpword = "tucan0";

// nomes das tabelas
$cs_session_tbl = "session";

// especificacoes da base de dados das anotacoes
$anota_dbhost = "coweb.icmc.usp.br";
$anota_dbname   = "groupnote";
$anota_dbuser = "groupnote";
$anota_dbpword = "groupnote";

// especificacoes da base de dados de usuarios
$user_dbhost = "coweb.icmc.usp.br";
$user_dbname   = "users";
$user_dbuser = "users";
$user_dbpword = "users";

// especificacoes da base de dados de cursos do eclass
$ce_dbhost = "catuaba.icmc.usp.br";
$ce_dbname = "eclassDB";
$ce_dbuser = "eclass";
$ce_dbpword = "qazxcde";


/**
* Configurações relativas ao CVS.
*/
// IP do servidor de CVS
$CVS_HOST = "143.107.231.206";
// Repositório CVS a ser utilizado (caminho no servidor CVS)
$CVS_REPOSITORY = "/usr/local/coweb";
// Nome do usuário a ser utilizado
$CVS_USERNAME = "anonymous";
// Senha do usuário do CVS
$CVS_PASSWORD = ""
// Caminho a ser utilizado para o checkout dos arquivos
$CVS_CHECKOUT_PATH = "/coteia/";


// Endereço (URL) da CoTeia
$URL_COWEB = "http://coweb.icmc.usp.br/coweb/";

// Nome do administrador
$ADMIN = "Carlos Arruda Junior";

// Email do administrador
$ADMIN_MAIL = "credajun@icmc.usp.br";

//ex. de $URL_IMG = "/coweb/imagem" 
$URL_IMG = $URL_SERVER . "/images";

$PATH_COWEB = "/var/www/html/coweb";

// Local em que estão armazenados os arquivos enviados (upload)
$PATH_ARQUIVOS = $PATH_COWEB . "/files";

//LINUX:ex. de $DIR_UPLOAD = "/xxx/upload"
$PATH_UPLOAD = $PATH_COWEB . "/upload";

$URL_UPLOAD = $URL_SERVER . "/upload";

$PATH_JAVA = "/usr/java/j2sdk1.4.0_01/bin/java";

//WIN:ex. de $PATH_XT = ""
//LINUX:ex. de $PATH_XT = "/home/xt/xt.jar:/home/xt/sax.jar:/home/xp/xp.jar"
$PATH_XT = "/home/xt/xt.jar:/home/xt/sax.jar:/home/xp/xp.jar";

$PATH_XHTML = $PATH_COWEB . "/files";

$PATH_XSL = $PATH_COWEB . "/coteia.xsl";

$PATH_XML = $PATH_COWEB . "/xml";
