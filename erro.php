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
<img src=\"$PATH_IMG/viewbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/editbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/historybw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/indicebw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/mapbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/changesbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/uploadbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/searchbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/helpbw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/chatbw.gif\" border=\"0\">
<img src=\"$PATH_IMG/notebw.gif\" border=\"0\"/>
<img src=\"$PATH_IMG/printbw.gif\" border=\"0\"/>
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
