<html>

<head>
	<title>Search</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="coteia.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="coteia.js"></script>
</head>

<body>

<?php
/*
* Funcionalidade: Pesquisa por paginas CoWeb.
* Opções de Busca: Titulo, Conteudo e Palavra-Chave.
*/
include_once("function.inc");

//encontra id_swiki
$get_swiki = explode(".",$ident);
$id_swiki = $get_swiki[0];  

include( "toolbar.inc" );

if ($submit_btn=="submit") {
	global $dbname;

	$dbh = db_connect();

	# seleciona base de dados
	mysql_select_db($dbname,$dbh);
   
	$src[1] = $tit;
	$src[2] = $con;
	$src[3] = $pch;

	$search_tratamento = tratamento(0,0,0,0,$src);
	$tit = trim($search_tratamento["key1"]);
	$con = trim($search_tratamento["key2"]);
	$pch = trim($search_tratamento["key3"]);

	if ( $search_select == 0 ) { 
		/*
		* Buscar todas as paginas de todos os swikis por titulo, conteudo ou palavra-chave.
		* Busca swikis e paginas por titulo.
		*/
		$count = 0;
		$resultA = mysql_query("SELECT id,titulo FROM swiki order by titulo",$dbh);
		while ($tuplaA = mysql_fetch_array($resultA) ) {
			$tituloA = $tuplaA[titulo];
			$idA = $tuplaA[id];
			$sql = "SELECT DISTINCT paginas.titulo, paginas.ident FROM paginas,gets,swiki WHERE gets.id_sw = $idA AND gets.id_pag=paginas.ident ";
			if ($cbox_tit) {
				$sql = $sql . "AND paginas.titulo LIKE \"%$tit%\"";
			}
			if ($cbox_con) {
				$sql = $sql . "AND paginas.conteudo LIKE \"%$con%\"";
			}
			if ($cbox_pch) {
				$sql = $sql . "AND (paginas.kwd1=\"$pch\" OR paginas.kwd2=\"$pch\" OR paginas.kwd3=\"$pch\")";
			}
			$resultB = mysql_query($sql,$dbh);
			$num_rows = mysql_num_rows($resultB);
			if ($num_rows != "0") {
				echo "<br />Em <b>$tituloA</b>:<br />";
				while ($tuplaB = mysql_fetch_array($resultB)){
					$tituloB = $tuplaB[titulo];
					$idB = $tuplaB[ident];
					$count++;
					echo "<li><a href=\"show.php?ident=$idB\">$tituloB</a></li>";
				}
				echo "<br />";
			}
		}
		echo "<br />Resultado da Busca:  $count página(s).";
	} else {
		/* Buscar pagina de uma swiki por título, ou conteúdo, ou palavra-chave. */
		$sql = "SELECT DISTINCT paginas.titulo, paginas.ident FROM paginas,gets WHERE gets.id_sw = $search_select AND gets.id_pag=paginas.ident";
		if ($cbox_tit) {
			$sql = $sql."AND paginas.titulo LIKE \"%$tit%\"";
		}
		if ($cbox_con) {
			$sql = $sql."AND paginas.conteudo LIKE \"%$con%\"";
		}
		if ($cbox_pch) {
			$sql = $sql."AND (paginas.kwd1=\"$pch\"OR paginas.kwd2=\"$pch\" OR paginas.kwd3=\"$pch\")";
		}
		$result = mysql_query($sql,$dbh);
		echo "<br />";
		$count = 0;
		while ($tupla = mysql_fetch_array($result)) {
			$titulo = $tupla[titulo];
			$id = $tupla[ident];
			$count++;
			echo "<li><a href=\"show.php?ident=$id\">$titulo</a>";
		}
		echo "<br /><br />Resultado da Busca:  $count página(s).";
	}
} else {
?>
<form method="post" action="search.php" name="pesquisa">

<select name="search_select">
	<option value="0">Em todas as Swikis</option>
	<?php
		global $dbname;

		$dbh = db_connect();
		# seleciona base de dados
		mysql_select_db($dbname,$dbh);

		$sql = mysql_query("SELECT id,titulo FROM swiki order by titulo",$dbh);
		while ($tupla = mysql_fetch_array($sql)){
			$titulo = $tupla[titulo];
			$id_titulo = $tupla[id];
			if ($id_titulo!=$id_swiki) {
				echo "<option value=\"$id_titulo\">Em $titulo</option>";
			} else {
				echo "<option value=\"$id_titulo\" selected>Em $titulo</option>";
			}
		}
	?>
	</select>
	<br />
	<table border="1">
	<tr>
		<td><input type="checkbox" name="cbox_tit" />Por <b>Título</b> da página</td>
		<td><input type="checkbox" name="cbox_con" />Por <b>Conteúdo</b> da página</td>
		<td><input type="checkbox" name="cbox_pch" />Por <b>Palavras-chave</b></td>
	</tr>
  <tr>
		<td><input type="text" name="tit" width="300" onChange="window.document.pesquisa.cbox_tit.checked=true;return false;" /></td>
		<td><input type="text" name="con" width="300" onChange="window.document.pesquisa.cbox_con.checked=true;return false;" /></td>
		<td><input type="text" name="pch" width="200" onChange="window.document.pesquisa.cbox_pch.checked=true;return false;" /></td>
	</tr>
  </table>
	<br />
  <input type="submit" name="submit_btn" value="submit" />
	<input type="hidden" name="ident" value="<?php echo $ident;?>">  
</form>
<?
}
?>
</body>
</html>
