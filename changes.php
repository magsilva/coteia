<?
	include("function.inc");
?>
<HTML>
<HEAD>
<TITLE> Recent Changes </TITLE>
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
		<IMG src="<?echo $URL_IMG?>/viewbw.gif" border=0>
		<IMG src="<?echo $URL_IMG?>/editbw.gif" border=0>
                <A href="history.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/history.gif" border=0></A>
		<A href="mostra.php?ident=<?echo $id_swiki?>">
		<IMG src="<?echo $URL_IMG?>/indice.gif" border=0></A>
		<A href="JavaScript:AbreMapa(<?echo $id_swiki?>)">
	        <img src="<?echo $URL_IMG?>/map.gif" border="0"/></A>
		<A href="changes.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/changes.gif" border=0></A>
                <A href="upload.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/upload.gif" border=0></A>
		<A href="search.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/search.gif" border=0></A>
		<A href="help.php">
		<IMG src="<?echo $URL_IMG?>/help.gif" border=0></A>
		<A href="JavaScript:AbreChat(<?echo $id_swiki?>)">
		<img src="<?echo $URL_IMG?>/chat.gif" border="0"/></A>
		<img src="<?echo $URL_IMG?>/notebw.gif" border="0"/>
		<A href="JavaScript:Imprime()">
		<img src="<?echo $URL_IMG?>/print.gif" border="0"/></A>
<br><br>
<?
//no menu superior: ident = id da swiki
//upload e history deveriam estar com id de paginas

if ($submit_btn=="submit") {

        global $dbname;

	$dbh = db_connect();

        # seleciona base de dados
        mysql_select_db($dbname,$dbh);
 	  
   if ($changes_select==0) { 
	 /* Buscar todas as paginas de todos os swikis ordenadas por data. */  

	   $resultA = mysql_query("select id,titulo from swiki order by titulo",$dbh);
	   echo "<BR>";
	   while ($tuplaA = mysql_fetch_array($resultA)){
	   $tituloA = $tuplaA[titulo];
	   $idA = $tuplaA[id];
	   $resultB = mysql_query("SELECT paginas.data_ultversao, paginas.titulo,paginas.ident FROM paginas, gets WHERE gets.id_sw = $idA AND gets.id_pag =paginas.ident ORDER BY paginas.data_ultversao DESC",$dbh);
           $num_rows = mysql_num_rows($resultB);
           if ($num_rows != "0") {
	   echo "<BR> <B>$tituloA:</B>";
	   while ($tuplaB = mysql_fetch_array($resultB)){
		   
		   //acerta o formato da data
	 	   $datetime = explode(" ",$tuplaB[data_ultversao]);
		   $date = explode("-",$datetime[0]);
	 	   $data_formato_correto = $date[2]."-".$date[1]."-".$date[0]." ".$datetime[1];
		   
		   $tituloB = $tuplaB[titulo];
		   $idB = $tuplaB[ident];
		   echo "<BR>\t[$data_formato_correto] - <a href=mostra.php?ident=$idB>$tituloB</a>";
		  }	// fim while B
		   echo "<BR>";
		}
	} //fim while A  
   } else {
	 /* Buscar as paginas de 1 swiki ordenadas por data. 
	    $select é a numero de identificacao do swiki */
	   $sql = "SELECT paginas.data_ultversao, paginas.titulo, paginas.ident FROM paginas, gets WHERE gets.id_sw=$changes_select AND gets.id_pag =paginas.ident ORDER BY paginas.data_ultversao DESC";
	   $result = mysql_query($sql,$dbh);
           $num_rows = mysql_num_rows($result);
           if ($num_rows != "0") {
	   while ($tupla = mysql_fetch_array($result)){
			
			//acerta o formato da data
		        $datetime = explode(" ",$tupla[data_ultversao]);
			$date = explode("-",$datetime[0]);
			$data_formato_correto = $date[2]."-".$date[1]."-".$date[0]." ".$datetime[1];
			
			$titulo = $tupla[titulo];
			$id = $tupla[ident];
			echo "<BR><BR> [$data_formato_correto] - <a href=mostra.php?ident=$id>$titulo</a>";
		}	// fim while   
 	 }
	 else echo "Não existem páginas criadas.";
	 } //fim if de linha
} else {
?>
<FORM METHOD=POST ACTION="changes.php">
  <SELECT NAME="changes_select">
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
			echo "<OPTION VALUE=$id_titulo >Em $titulo</OPTION>";
		}
                else {
                	echo "<OPTION VALUE=$id_titulo SELECTED>Em $titulo</OPTION>";
		}
	     }
   	     ?>
</SELECT>
<INPUT TYPE="submit" NAME="submit_btn" VALUE="submit">
<INPUT TYPE="hidden" name="ident" value="<?echo $ident?>"> 
</FORM>
<?
  } /* fim do else */
?>
</BODY>
</HTML>
