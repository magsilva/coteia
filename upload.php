<?

include_once("function.inc");

$dbh = db_connect();

# seleciona base de dados
mysql_select_db($dbname,$dbh);

$ok = 0;
if ($act == "upload") {
	if ((is_uploaded_file($HTTP_POST_FILES['uploads']['tmp_name'])) && (!stristr($HTTP_POST_FILES['uploads']['name'],".php"))  && (!stristr($HTTP_POST_FILES['uploads']['name'],".jsp")) && (!stristr($HTTP_POST_FILES['uploads']['name'],".cgi"))) {
		$realname = $HTTP_POST_FILES['uploads']['name'];
		$path = $coursename."/".$realname;
		if (file_exists($path)) {
			$ok = 3;
		}	else {
			copy($HTTP_POST_FILES['uploads']['tmp_name'],$path);
			chmod($path, 0444);
			$ok = 1;
		}
	} else {
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

?>
<html>

<head>
	<title>Upload - CoTeia</title>
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="70%" bgcolor="#E1F0FF" bordercolor="#C0C0C0" align="center" border="1">
<tr>
	<td>
		<img src="<?php echo "$URL_IMG/Manager.png";?>" />
		<b>CoTeia</b>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo "$URL_IMG/Dir_open.png";?>" />
		<b><?php echo "Swiki: $titulo";?></b>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $URL_IMG;?>/Cvs.png" /><b>Lista de Arquivos</b>
		<div align="center">
			<form name="checkout">
			<br />
			<select name="lista_arquivos" size="10" style="width: 60%">
			<?php
				// Abre lista_arquivos
				$a = array();
				$fd = opendir( "$PATH_UPLOAD/$id_sw/" );
				while( $entry = readdir($fd) ) {
					if (!eregi("\.$",$entry)) {
						array_push($a,$entry);
					}
				}
				closedir($fd);
				// Ordena em ordem alfabetica
				sort($a);
				reset($a);
				while (list ($key,$val) = each ($a)) {
					echo "<option value=\"$val\">$val</option>";
				}
			?>
			</select>
			<br />
			<input type="button" name="abrir_arquivo" value="Abrir arquivo" OnClick="AbreArq();" />
			</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $URL_IMG;?>/files2upload.png" /><b>Upload</b>
		<div align="center">
			<form enctype="multipart/form-data" method="post" action="<?php echo "upload.php?ident=$ident&amp;act=upload";?>" target="base">
				<input type="hidden" name="coursename" value="<?php echo "$PATH_UPLOAD/$id_sw";?>" />
				<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
				<br />
				<input type="file" size="40" name="uploads" />
				<br />
				<input type="submit" name="fazer_upload" value="Upload" />
			</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div align="center">
			<a href="mostra.php?ident=<?php echo $ident;?>" target="_parent"><img src="<?php echo $URL_IMG;?>/back.png" />Voltar</a>
		</div>
	</td>
</tr>

</table>

</body>

</html>
