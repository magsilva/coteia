<?
//st = 1 => erro de identificador ao criar nova pagina
//st = 2 => nao criou arquivo fisico
//st = 3 => erro de indexador ao criar nova pagina

include_once("function.inc");

$d = getdate();
$data=$d["mday"]."-".$d["mon"]."-".$d["year"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

$ip = $REMOTE_ADDR;
$host = getHostByAddr($REMOTE_ADDR);
$pagina = $PHP_SELF;
$browser = $HTTP_USER_AGENT; 

$log = $data." | ".$ip." | ".$host." | ".$pagina." | ".$st." : ".$ident." | ".$browser."\n";
$fp = @fopen("log.txt","a");
@fputs($fp,$log);
@fclose ($fp);

echo "<!doctype html public \"-//w3c//dtd html 4.0 transitional//en\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">
<title>Falhou !!</title>
</head>
<BODY text=#000000 vLink=#000000 aLink=#ffff00 link=#000080 bgColor=#ffffff>
<img src=\"$URL_IMG/viewbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/editbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/historybw.png\" border=\"0\"/>
<img src=\"$URL_IMG/indicebw.png\" border=\"0\"/>
<img src=\"$URL_IMG/mapbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/changesbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/uploadbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/searchbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/helpbw.png\" border=\"0\"/>
<img src=\"$URL_IMG/chatbw.png\" border=\"0\">
<img src=\"$URL_IMG/notebw.png\" border=\"0\"/>
<img src=\"$URL_IMG/printbw.png\" border=\"0\"/>
<br><br>
<br><center>
<font FACE=\"Arial\" size=\"3\">";
if ($st == 2) {
echo "<p>Falha na estrutura XML !!</p>
<p>Lembre-se: <b>Sempre</b> XML bem-formado com elementos válidos !</p>
<p>As tags (marcações) podem estar incorretas quanto a:</p>
<p><li>sintaxe (com atributos sem aspas ou incorretamente fechadas)</p>
<p><li>aninhamento (não é permitido o uso de tags aninhadas)</p>
<br><br>
<p><strong><a href=\"javascript:history.go(-1)\">voltar</a></strong></p></font></center>
</body>
</html>";
}
else {
echo "<p>Falha na estrutura de edi&ccedil;&atilde;o / cria&ccedil;&atilde;o de documentos CoTeia!!</p>
<p><b>Posss&iacute;veis Problemas:</b></p>
<p><li>Identificador inv&aacute;lido</p>
<p><li>P&aacute;gina existente (n&atilde;o pode ser recriada)</p>
<br><br>
<p><strong><a href=\"javascript:history.go(-1)\">voltar</a></strong></p></font></center>
</body>
</html>";
}
?>
