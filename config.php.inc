<?php

/**
* Configuração da base de dados principal da CoTeia.
*/
$dbhost = "";
$dbname = "";
$dbuser = "";
$dbpass = "";

/**
* Configuração da base de dados do serviço de chat suportada pelo CoTeia.
*/
$cs_dbhost = "";
$cs_dbname = "";
$cs_dbuser = "";
$cs_dbpword = "";

/**
* Configuração da base de dados do serviço de anotações suportado pela CoTeia.
*/
$anota_dbhost = "";
$anota_dbname = "";
$anota_dbuser = "";
$anota_dbpword = "";

/**
* Configuração da base de dados do suporte à base de usuários compartilhadas
* utilizável pela CoTeia.
*/
$user_dbhost = "";
$user_dbname = "";
$user_dbuser = "";
$user_dbpword = "";

/**
* Configuração da base de dados de cursos do eClass suportada pela CoTeia.
*/
$ce_dbhost = "";
$ce_dbname = "";
$ce_dbuser = "";
$ce_dbpword = "";

/**
* Diretório em que está instalada a CoTeia.
*/
$PATH_COWEB = "";

/**
* Configurações relativas ao servidor CVS utilizado.
*/
$CVS_HOST = "";
$CVS_REPOSITORY = "";
$CVS_MODULE = "";
$CVS_USERNAME = "";
$CVS_PASSWORD = "";
$CVS_PASSFILE = $PATH_COWEB . "/.cvspass"; // Esta variável geralmente não precisa ser reconfigurada.

/**
* Host em que está hospedada a CoTeia.
*/
$HOST_COWEB = "";

/**
* Endereço (URL) da CoTeia.
*/
$URL_COWEB = "http://" . $HOST_COWEB . "/coweb"; // Geralmente somente a última string deve ser alterada.

/**
* Informações sobre o administrador.
*/
$ADMIN = "";
$ADMIN_MAIL = "";
$ADMIN_PASSWORD = "";

/**
* Caminho do Java (o executável "java").
*/
$PATH_JAVA = "";



/****************************************************************************/
/* Parâmetros que geralmente dispensam alteração                            */
/****************************************************************************/

// Classpath dos bibliotecas para transformação XSLT.
$PATH_XT = $PATH_COWEB . "/libs/xt.jar:" . $PATH_COWEB . "/libs/xp.jar";

// Endereço das imagens 
$URL_IMG = $URL_COWEB . "/images";

// Local em que estão armazenados os arquivos
$PATH_ARQUIVOS = $PATH_COWEB . "/files";

// Local em que estão armazenados os arquivos enviados (upload)
$PATH_UPLOAD = $PATH_COWEB . "/upload";

// URL para acesso aos arquivos enviados (upload)
$URL_UPLOAD = $URL_SERVER . "/upload";

// Diretório das páginas geradas pela wiki
$PATH_XHTML = $PATH_COWEB . "/files";

// Arquivo contendo a folha de estilo a ser aplicada.
$PATH_XSL = $PATH_COWEB . "/coteia.xsl";

// Diretório em que serão armazenadas as folhas XML.
$PATH_XML = $PATH_COWEB . "/xml";
