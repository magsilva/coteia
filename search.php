<?
	include("function.inc");
?>
<HTML>
<HEAD>
<TITLE> Search </TITLE>
<META http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>
<META content=\"MSHTML 5.50.4134.600\" name=\"GENERATOR\"/>
<script language="JavaScript">
function AbreMapa(id)
        {
	window.open('map.php?id='+id,'janelamap','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=520,height=480');
        }
function AbreChat(swiki)
{
	window.open('chat.php?swiki='+swiki,'janela_chat','toolbar=no,directories=no,location=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=700,height=500');
}
function Imprime()
{
	window.print();  
}
</script> 
</HEAD>
<?

                //encontra id_swiki
                $get_swiki = explode(".",$ident);
                $id_swiki = $get_swiki[0];  

?>
<BODY text=#000000 vLink=#0000cc aLink=#ffff00 link=#cc0000 bgColor=#ffffff>
	<IMG alt="View this Page" src="<?echo $URL_IMG?>/viewbw.png" border=0>
	<IMG alt="Edit this Page" src="<?echo $URL_IMG?>/editbw.png" border=0>
	<A href="history.php?ident=<?echo $ident?>">
	<IMG alt="History of this Page" src="<?echo $URL_IMG?>/history.png" border=0></A>
	<A href="mostra.php?ident=<?echo $id_swiki?>">
	<IMG alt="Top of the Swiki" src="<?echo $URL_IMG?>/indice.png" border=0></A>
	<A href="JavaScript:AbreMapa(<?echo $id_swiki?>)">
	<img alt="Mapa do Site" src="<?echo $URL_IMG?>/map.png" border="0"/></A>
	<A href="changes.php?ident=<?echo $ident?>">
	<IMG alt="Recent Changes" src="<?echo $URL_IMG?>/changes.png" border=0></A>
	<A href="upload.php?ident=<?echo $ident?>">
	<IMG alt="File Attachments" src="<?echo $URL_IMG?>/upload.png" border=0></A>
	<A href="search.php?ident=<?echo $ident?>">
	<IMG alt="Search the Swiki" src="<?echo $URL_IMG?>/search.png" border=0></A>
	<A href="help.php">
	<IMG alt="Help Guide" src="<?echo $URL_IMG?>/help.png" border=0></A>
	<A href="JavaScript:AbreChat(<?echo $id_swiki?>)">
	<img alt="ChatServer" src="<?echo $URL_IMG?>/chat.png" border="0"/></A>
	<img alt="GroupNote" src="<?echo $URL_IMG?>/notebw.png" border="0"/>
	<A href="JavaScript:Imprime()">
	<img alt="Print this Page" src="<?echo $URL_IMG?>/print.png" border="0"/></A>
<br><br>
<?
/*
* Search.php
*
* Funcionalidade: Pesquisa por paginas CoWeb.
* Opcoes de Busca: Titulo, Conteudo e Palavra-Chave
*
*/   
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
