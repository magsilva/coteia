<html>

<head>
	<title>Search</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
`	<link href="coteia.css" rel="stylesheet" type="text/css" />
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

include( "toolbar.php" );

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

     if ($search_select==0) { 
	 /* Buscar todas as paginas de todos os swikis por titulo, conteudo ou palavra-chave.   
	    Busca swikis e paginas por titulo */
	   $count = 0;
	   $resultA = mysql_query("SELECT id,titulo FROM swiki order by titulo",$dbh);
	   while ($tuplaA = mysql_fetch_array($resultA)){
		 $tituloA = $tuplaA[titulo];
		 $idA = $tuplaA[id];
		 $sql = "SELECT DISTINCT paginas.titulo, paginas.ident FROM paginas,gets,swiki WHERE gets.id_sw = $idA AND gets.id_pag=paginas.ident ";
	     if ($cbox_tit) { $sql = $sql."AND paginas.titulo LIKE\"%$tit%\" ";};
	     if ($cbox_con) { $sql = $sql."AND paginas.conteudo LIKE\"%$con%\" ";};
	     if ($cbox_pch) { $sql = $sql."AND (paginas.kwd1=\"$pch\" OR paginas.kwd2=\"$pch\" OR paginas.kwd3=\"$pch\")";};
		 $resultB = mysql_query($sql,$dbh);
	     $num_rows = mysql_num_rows($resultB);
	     if ($num_rows != "0") {
	     echo "<BR>Em <B>$tituloA</B>:<BR>";
	     while ($tuplaB = mysql_fetch_array($resultB)){
		   $tituloB = $tuplaB[titulo];
		   $idB = $tuplaB[ident];
		   $count++;
		   echo "<LI><a href=mostra.php?ident=$idB>$tituloB</a>";
		  }	// fim while B
		  echo "<BR>";
	      } //fim if de linha
 	 } //fim while A 
	   echo "<BR>Resultado da Busca:  $count página(s).";		 
	 } else {
	 /* Buscar pagina de uma swiki por titulo, ou conteudo, ou palavra chave */
	   $sql = "SELECT DISTINCT paginas.titulo, paginas.ident FROM paginas,gets WHERE gets.id_sw = $search_select AND gets.id_pag=paginas.ident ";
	   if ($cbox_tit) { $sql = $sql."AND paginas.titulo LIKE\"%$tit%\" ";};
	   if ($cbox_con) { $sql = $sql."AND paginas.conteudo LIKE\"%$con%\" ";};
	   if ($cbox_pch) { $sql = $sql."AND (paginas.kwd1=\"$pch\"OR paginas.kwd2=\"$pch\" OR paginas.kwd3=\"$pch\")";};
	   $result = mysql_query($sql,$dbh);
	   echo "<BR>";
	   $count = 0;
	   while ($tupla = mysql_fetch_array($result)){
			$titulo = $tupla[titulo];
			$id = $tupla[ident];
			$count++;
			echo "<LI><a href=mostra.php?ident=$id>$titulo</a>";
	   }	// fim while
	   echo "<BR><BR>Resultado da Busca:  $count página(s).";		 
   
  	 } //fim else
  } else {
?>
<FORM METHOD=POST ACTION="search.php" NAME="pesquisa">
 <SELECT NAME="search_select">
            <OPTION VALUE="0"> Em todas as Swikis</OPTION>
            <?
   global $dbname;

   $dbh = db_connect();

   # seleciona base de dados
   mysql_select_db($dbname,$dbh);

                $sql = mysql_query("SELECT id,titulo FROM swiki order by titulo",$dbh);
                while ($tupla = mysql_fetch_array($sql)){
                        $titulo = $tupla[titulo];
                        $id_titulo = $tupla[id];
                        if ($id_titulo!=$id_swiki) {
                        echo "<OPTION VALUE=$id_titulo >Em $titulo</OPTION>";}
                        else {
                        echo "<OPTION VALUE=$id_titulo SELECTED>Em $titulo</OPTION>";}
                }
                ?>
  </SELECT><BR>
  <TABLE BORDER="1"> 
  <TR>
	<TD><INPUT TYPE="checkbox" NAME="cbox_tit">Por <B>Título</B> da página</TD>
	<TD><INPUT TYPE="checkbox" NAME="cbox_con">Por <B>Conteúdo</B> da página</TD>
	<TD><INPUT TYPE="checkbox" NAME="cbox_pch">Por <B>Palavras-Chave</B></TD>
  </TR>
  <TR>
	<TD><INPUT TYPE="text" NAME="tit" WIDTH="300" onBlur="window.document.pesquisa.cbox_tit.checked=true;return false;"></TD>
	<TD><INPUT TYPE="text" NAME="con" WIDTH="300" onBlur="window.document.pesquisa.cbox_con.checked=true;return false;"></TD>
	<TD><INPUT TYPE="text" NAME="pch" WIDTH="200" onBlur="window.document.pesquisa.cbox_pch.checked=true;return false;"></TD>
  </TR>
  </TABLE><BR>	
  <INPUT TYPE="submit" NAME="submit_btn" VALUE="submit">
<INPUT TYPE="hidden" name="ident" value="<?echo $ident?>">  
</FORM>
<?
  } /* fim do else */
?>
</BODY>
</HTML>
