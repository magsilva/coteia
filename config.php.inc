<?php

/**
* Configuração da base de dados principal da CoTeia.
*/
$dbhost = "localhost";
$dbname = "coteia";
$dbuser = "coweb";
$dbpass = "ajnusleilamnoa";

/**
* Configuração da base de dados do serviço de anotações suportado pela CoTeia.
*/
$anota_dbhost = $dbhost;
$anota_dbname = "coteia_anotacoes";
$anota_dbuser = $dbuser;
$anota_dbpword = $dbpass;

/**
* Configuração da base de dados do suporte à base de usuários compartilhadas
* utilizável pela CoTeia.
*/
$user_dbhost = $dbhost;
$user_dbname = "coteia_users";
$user_dbuser = $dbuser;
$user_dbpword = $dbpass;

/**
* Configuração da base de dados de cursos do eClass suportada pela CoTeia.
*/
$ce_dbhost = $dbhost;
$ce_dbname = "coteia_eclass";
$ce_dbuser = $dbuser;
$ce_dbpword = $dbpass;

/**
* Diretório em que está instalada a CoTeia.
*/
$PATH_COWEB = "/home/anselmo/public_html/CoTeia";

/**
* Configurações relativas ao servidor CVS utilizado.
*/
$CVS_HOST = "vinho.intermidia.icmc.usp.br";
$CVS_REPOSITORY = "srv/local/coweb-develop";
$CVS_MODULE = "html";
$CVS_USERNAME = "developer";
$CVS_PASSWORD = "";
$CVS_CHECKOUT_DIR = $PATH_COWEB . "/repository"; // Esta variável geralmente não precisa ser reconfigurada.
$CVS_PASSFILE = $CVS_CHECKOUT_DIR . "/.cvspass"; // Esta variável geralmente não precisa ser reconfigurada.

/**
* Host em que está hospedada a CoTeia.
*/
$HOST_COWEB = "vinho.intermidia.icmc.usp.br";

/**
* Endereço (URL) da CoTeia.
*/
$URL_COWEB = "http://" . $HOST_COWEB . "/~anselmo/CoTeia"; // Geralmente somente a última string deve ser alterada.

/**
* Informações sobre o administrador.
*/
$ADMIN = "admin";
$ADMIN_MAIL = "aemj@grad.icmc.usp.br";
$ADMIN_PASSWORD = "teste";

/**
* Caminho do Java (o executável "java").
*/
$PATH_JAVA = "/home/silvana/j2sdk_nb/j2sdk1.4.2/bin/java";



/****************************************************************************/
/* Parâmetros que geralmente dispensam alteração                            */
/****************************************************************************/

// Classpath dos bibliotecas para transformação XSLT.
$PATH_XT = $PATH_COWEB . "/libs/xt.jar:" . $PATH_COWEB . "/libs/xp.jar";

// Endereço das imagens
$URL_IMG = $URL_COWEB . "/images";

// URL para acesso aos arquivos enviados (upload)
$URL_UPLOAD = $URL_COWEB . "/upload";

// Local em que estão armazenados os arquivos
$PATH_ARQUIVOS = $PATH_COWEB . "/files";

// Local em que estão armazenados os arquivos enviados (upload)
$PATH_UPLOAD = $PATH_COWEB . "/upload";

// Diretório das páginas geradas pela wiki
$PATH_XHTML = $PATH_COWEB . "/files";

// Arquivo contendo a folha de estilo a ser aplicada.
$PATH_XSL = $PATH_COWEB . "/coteia.xsl";

// Diretório em que serão armazenadas as folhas XML.
$PATH_XML = $PATH_COWEB . "/xml";
