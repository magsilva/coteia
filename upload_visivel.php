<?

include("function.inc");

$dbh = db_connect();

# seleciona base de dados
mysql_select_db($dbname,$dbh);

$ok = 0;
if ($act == "upload")
{
	if ((is_uploaded_file($HTTP_POST_FILES['uploads']['tmp_name'])) && (!stristr($HTTP_POST_FILES['uploads']['name'],".php"))  && (!stristr($HTTP_POST_FILES['uploads']['name'],".jsp")) && (!stristr($HTTP_POST_FILES['uploads']['name'],".cgi"))) 
	{
	$realname = $HTTP_POST_FILES['uploads']['name'];
	$path = $coursename."/".$realname;
		if (file_exists($path)) {
			$ok = 3;
		}
		else 
		{
			copy($HTTP_POST_FILES['uploads']['tmp_name'],$path);
			chmod($path, 0444);
			$ok = 1;
		}
	}
	else
	{
		$ok = 2;
	}
}

$query_swiki = "SELECT id_sw from gets where (id_pag='$ident')";
$sql_swiki = mysql_query($query_swiki,$dbh);
$tupla = mysql_fetch_array($sql_swiki);
$id_sw = $tupla[id_sw];

$query_swiki1 = "SELECT titulo from swiki where (id='$id_sw')";
$sql_swiki1 = mysql_query($query_swiki1,$dbh);
$tupla1 = mysql_fetch_array($sql_swiki1);
$titulo = $tupla1[titulo];

echo "<html>
<head><title>Upload - CoTeia</title>
<script>
function AbreArq()
{
if (document.checkout.lista_arquivos)
 var IndiceArq = document.checkout.lista_arquivos.options.selectedIndex;
if (IndiceArq >=0)
{
window.open(\"checkout.php?swiki=$id_sw&arq=\"+document.checkout.lista_arquivos.options[IndiceArq].value,\"janela\",\"toolbar=no,directories=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=500,height=430\");
return true;
}
 if (IndiceArq == -1)
{
 alert(\"Por favor, selecione um arquivo !\");
 return false;
}}
</script>
</head>
<body bgcolor=#FFFFFF><br><br>
<center>
<table BORDER CELLSPACING=0 CELLPADDING=0 WIDTH=760 BGCOLOR=#E1F0FF bordercolor=#C0C0C0 bordercolordark=#C0C0C0 bordercolorlight=#C0C0C0>
<tr>
<td colspan=2 bgcolor=#A8BAC3><img src=\"$PATH_IMG/Manager.gif\" width=24 height=24> 
<font color=#000088><font face=arial,helvetica><font size=+2>CoTeia
</font></font></font>
</td></tr>
<tr><td colspan=2 WIDTH=50% BGCOLOR=#C0C0C0><img src=\"$PATH_IMG/Dir_open.gif\" width=24 height=24>
<font color=#000080><font face=arial,helvetica><font size=+1>&nbsp;Swiki:&nbsp; $titulo 
</font></font></font>
</td></tr>
<tr><td>
<table BORDER WIDTH=100% bgcolor=#E1F0FF>
<tr><td>
<img src=\"$PATH_IMG/Cvs.gif\" width=24 height=24>&nbsp;&nbsp;<b>Lista de Arquivos</b>
<form name=checkout>
<center>
<p><select name=lista_arquivos size=5>";
//abre lista_arquivos

$a = array();
$fd = opendir("$PATH_UPLOAD/$id_sw/"); 
	while($entry = readdir($fd)) {
        	if (!eregi("\.$",$entry)) {  
		array_push($a,$entry);   
                }
        }
closedir($fd);

//ordena em ordem alfabetica
sort($a);
reset($a);

while(list ($key,$val) = each ($a)) {
	echo "<option value=$val>$val</option>";
}

echo "<option>---------------------------------------------------------</option>
</select></p>
</center>
<p><center><input type=button name=abrir_arquivo value=Abrir&nbsp;Arquivo OnClick=AbreArq();></center><p>
</form>
</td></tr>
<tr><td><img src=\"$PATH_IMG/files2upload.gif\"><b>&nbsp;&nbsp;Upload</b>
<form enctype=multipart/form-data method=POST action=\"upload_visivel.php?ident=$ident&act=upload\" target=\"base\"> 
<input type=hidden name=coursename value=$PATH_UPLOAD/$id_sw> 
<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"10000000\">
<p><center><input type=file size=40 name=uploads></center></p>
<p><center><input type=submit name=fazer_upload value=Upload></center></p>";
if (($ok == 1) || ($ok == 2) || ($ok == 3))
{
   if ($ok == 1)
   {
      echo "<script>alert('O arquivo $realname foi transferido com sucesso !');</script>";
   }
   elseif ($ok == 2)
   {
      echo "<script>alert('Erro ao gravar arquivo (Tamanho Máximo = 10 Mb) !');</script>";
   }
   elseif ($ok == 3)
   {
      echo "<script>alert('Este arquivo já existe !');</script>";
   }

}
echo "</td></tr>
</form>
</table>
<center> 
<tr><td>
<table border width=100% bgcolor=#C0C0C0>
<tr><td colspan=2 bgcolor=#C0C0C0>
<p><center><a href=\"mostra.php?ident=$ident\" target=\"_parent\"><img src=\"$PATH_IMG/back.gif\" border=0  width=30  height=20></a>
<font color=#000088><font face=arial,helvetica><fontsize=+2>&nbsp;&nbsp;&nbsp;Back
</font></font></font>
</td></tr> 
</table>
</center>
</td></tr>
</table>
</center>
</body></html>";
?>






