<?
/*
* Mostra.php
*
* Funcionalidade: Exibir no browser o arquivo que eh passado como parametro.
*
*/

include_once("function.inc");

                //encontra id_swiki
                $get_swiki = explode(".",$ident);
                $id_swiki = $get_swiki[0];

//conexao com BD
$dbh = db_connect();

# seleciona base de dados
mysql_select_db($dbname,$dbh);

$sql = mysql_query("SELECT status FROM swiki WHERE id='$id_swiki'");
$tupla = mysql_fetch_array($sql);
$status = $tupla[status];

if ($status == '1') {

	session_start("login");

	if(!(session_is_registered("namuser") AND session_is_registered("coduser"))) {
	echo "Essa é uma <b>área restrita</b>.<br>Você não tem permissão para acessá-la.";
	exit;
	}
}

$sucesso = @include("$PATH_ARQUIVOS/$ident.html"); 

if (!$sucesso) {
echo "<!doctype html public \"-//w3c//dtd html 4.0 transitional//en\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
<meta http-equiv=\"Pragma\" content=\"no-cache\">
<meta name=\"Autor\" content=\"credajun@icmc.usp.br\">
   <title>Página Não Encontrada</title>
</head>
<BODY text=#000000 vLink=#000000 aLink=#ffff00 link=#000080 bgColor=#ffffff>
<img src='$URL_IMG/viewbw.png' border='0'/>
<img src='$URL_IMG/editbw.png' border='0'/>
<img src='$URL_IMG/historybw.png' border='0'/>
<img src='$URL_IMG/indicebw.png' border='0'/>
<img src='$URL_IMG/mapbw.png' border='0'/>
<img src='$URL_IMG/changesbw.png' border='0'/>
<img src='$URL_IMG/uploadbw.png' border='0'/>
<img src='$URL_IMG/searchbw.png' border='0'/>
<img src='$URL_IMG/helpbw.png' border='0'/>
<img src='$URL_IMG/chatbw.png' border='0'>
<img src='$URL_IMG/notebw.png' border='0'/>
<img src='$URL_IMG/printbw.png' border='0'/>
<br><br>
<br>
<center>
<table BORDER=0 WIDTH=90%>
<th>
<font FACE=arial SIZE=3>
Desculpe! Esta página não foi encontrada em nosso servidor.
<br><br>
Em caso de dúvidas, entre em contato com o <a HREF=\"mailto:credajun@icmc.sc.usp.br\">administrador</a>
<br><br><center><a href=\"index.php\"><img src=\"$URL_IMG/home.png\" height=\"40\" border=\"0\"></a></center>
</font>
</th>
</table>
</body>
</html>"; }
?>
