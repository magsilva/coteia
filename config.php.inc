<?php

/****************************************************************************/
/* Parâmetros normalmente configuráveis.                                    */
/****************************************************************************/

/*********************************
* Configuração das bases de dados.
*/

// Configuração da base de dados principal da CoTeia
$dbhost = "localhost";
$dbname = "coteia";
$dbuser = "coteia";
$dbpass = "coteia";

// Configuração da base de dados do serviço de chat
$cs_dbhost = "catuaba.icmc.sc.usp.br";
$cs_dbname = "chatserver";
$cs_dbuser = "cs";
$cs_dbpword = "tucan0";
$cs_session_tbl = "session";

// Configuração da base de dados do serviço de anotações (opcional)
$anota_dbhost = "coweb.icmc.usp.br";
$anota_dbname   = "groupnote";
$anota_dbuser = "groupnote";
$anota_dbpword = "groupnote";

// Configuração da base de dados de usuários (opcional)
$user_dbhost = "coweb.icmc.usp.br";
$user_dbname   = "users";
$user_dbuser = "users";
$user_dbpword = "users";

// Configuração da base de dados de cursos do eClass (opcional)
$ce_dbhost = "catuaba.icmc.usp.br";
$ce_dbname = "eclassDB";
$ce_dbuser = "eclass";
$ce_dbpword = "qazxcde";


/********************************
* Configurações relativas ao CVS.
*/
// IP do servidor de CVS
$CVS_HOST = "vinho.intermidia.icmc.usp.br";
// Repositório CVS a ser utilizado (caminho no servidor CVS)
$CVS_REPOSITORY = "/usr/local/coweb";
// Nome do usuário a ser utilizado
$CVS_USERNAME = "anonymous";
// Senha do usuário do CVS
$CVS_PASSWORD = "";
// Caminho a ser utilizado para o checkout dos arquivos
$CVS_CHECKOUT_PATH = "/coteia/";

// Endereço (URL) da CoTeia
$URL_COWEB = "http://coweb.icmc.usp.br/coweb/";

// Nome do administrador
$ADMIN = "Carlos Arruda Junior";

// Email do administrador
$ADMIN_MAIL = "credajun@icmc.usp.br";

// Senha do administrador
$ADMIN_PASSWORD = "";

// Local em que está armazenada a CoTeia.
$PATH_COWEB = "/var/www/html/coweb";

// Caminho do Java (o executável "java"). 
$PATH_JAVA = "/opt/java/SUN-SDK-1.4.2_03/bin/java";

// Classpath dos bibliotecas para transformação XSLT.
$PATH_XT = $PATH_COWEB . "/libs/xt.jar:" . $PATH_COWEB . "/libs/xp.jar";


/****************************************************************************/
/* Parâmetros que geralmente dispensam alteração                            */
/****************************************************************************/

// Endereço das imagens 
$URL_IMG = $URL_SERVER . "/images";

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
