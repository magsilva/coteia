<html>
<head>
<title>CoTeia: Mapa do Site</title>
<style>
  BODY { font-family : Verdana,Arial; }
  TD   { font-family : Verdana,Arial; font-size : 8pt; }
  A    { text-decoration : none;  }
</style>
</head>
<body bgcolor=#E1F0FF link=#000000>
<table border=1 cellspacing=0 width=100%>
<tr><td bgcolor=#0099FF align=center><font size=2><b>
Mapa do Site
</b></font></td></tr>
</table>
<?

include_once("function.inc");

$dbh = db_connect();

# seleciona base de dados
mysql_select_db($dbname,$dbh);

$maxlevel=0;
$cnt=0;

$sql = mysql_query("SELECT ident,indexador FROM paginas WHERE ident='$id' or ident like '$id.%'",$dbh);

while ($tupla = mysql_fetch_array($sql)) 
  {
    $level = substr_count("$tupla[ident]",".");
    $level = $level + 1;	
    $tree[$cnt][0]= $level;
    $tree[$cnt][1]="$tupla[indexador]";
    $tree[$cnt][2]="mostra.php?ident="."$tupla[ident]";
    $tree[$cnt][3]=0;
    if ($tree[$cnt][0] > $maxlevel) $maxlevel=$tree[$cnt][0];
    $cnt++;
  }

  require "arvore_mapa.inc";

?>
<br>
<center>
<form>
<input type=button onClick="window.close();" value=Fechar>
</form></center>
</body>
</html>
