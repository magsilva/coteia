<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">
<META content="MSHTML 5.50.4134.600" name=GENERATOR>
<LINK REL="SHORTCUT ICON" HREF="imagem/Logo.ico">
<TITLE>CoTeia</TITLE>
</HEAD>
<BODY text="#000000" vLink="#cc0000" aLink="#cccc00" link="#0000ff" bgColor="#ffffff">
<H2>CoTeia</H2>
<?php
/*
* Index.php
*
* Funcionalidade: Tela inicial da CoTeia, aonde sao mostradas as swikis existentes.
*
*/

$today = getdate(); 
$month = $today['mon']; 
$mday = $today['mday']; 
$year = $today['year']; 

if ($month <= '6') $semester = 1;
	else $semester = 2;

echo "<b>Semestre Atual: $semester&ordm; de $year</b><br><br><ul>";

//semestre atual
$sem_atual = $semester.'_'.$year;

include("function.inc");

//conexao com BD
$dbh = db_connect();

# seleciona base de dados
mysql_select_db($dbname,$dbh);
        
$sql = mysql_query("SELECT id,status,titulo,id_chat,admin,admin_mail,visivel FROM swiki where (semestre='$sem_atual' || semestre='T') order by titulo",$dbh);	

while ($tupla = mysql_fetch_array($sql)) {

if  ($tupla[visivel]=='S') {

	if  (!empty($tupla[titulo])) {
		$final = $tupla[titulo];
	}
		
	$session_id = $tupla[id_chat];
	$admin = $tupla[admin];
	$admail = $tupla[admin_mail];

	$ident = $tupla[id];		
	$status = $tupla[status];		
	$sql_aux = mysql_query("SELECT id_sw FROM gets",$dbh);
		
	//$ident recebe o número de paginas ja criadas na swiki (relacionadas na tabela GETS) 
	$query_cont = "SELECT COUNT(*) as CONTADOR from gets where id_sw='$ident'";  
	$sql_cont = mysql_query("$query_cont",$dbh); 	
	$tupla_cont = mysql_fetch_array($sql_cont); 	 	
	$nro_paginas = $tupla_cont[CONTADOR]; 	 
	 		
	$controle=0;
	while (($tupla_aux = mysql_fetch_array($sql_aux)) && ($controle==0)) {
		$comp = $tupla_aux[id_sw];
		if ($ident==$comp) {
			$token=true; 
			$controle=1;
		}
		else $token=false;
		}

	if ($token==true) 
	{
		if ($status == '1') 
                	echo "<LI><a href=\"#\" onClick=\"window.open('login.php?id=$ident&token=1','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">$final</a> ($nro_paginas) p&aacute;gina(s):  (administrador: <A href=\"mailto:$admail\">$admin</A>)"; 
                else
			echo "<LI><a href=\"mostra.php?ident=$ident\" onMouseOver=\"window.status='$final'; return true\" onMouseOut=\"window.status=' '; return true\">$final</a> ($nro_paginas) p&aacute;gina(s):  (administrador: <a href=\"mailto:$admail\">$admin</a>)\n";
        }
	else    
	{
		$final_url = rawurlencode($final);
                if ($status == '1')
                	echo "<LI>$final<a href=\"#\" onClick=\"window.open('login.php?id=$ident&token=0&index=$final_url','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">[create]</a>";
               	else {
			echo "<LI>$final<a href=\"create.php?ident=$ident&index=$final_url\" onmouseover=\"window.status='$final'; return true\" onmouseout=\"window.status=' '; return true\">[create]</a>\n";
		}
        }
}//if
}//while

echo "</UL><br><b>Outras Entradas:</b><br><UL>";

$sql = mysql_query("SELECT id,status,titulo,id_chat,admin,admin_mail,visivel FROM swiki where (semestre<>'$sem_atual' && semestre<>'T') order by titulo",$dbh);	

while ($tupla = mysql_fetch_array($sql)) {

if  ($tupla[visivel]=='S') {

	if  (!empty($tupla[titulo])) {
		$final = $tupla[titulo];
	}
		
	$session_id = $tupla[id_chat];
	$admin = $tupla[admin];
	$admail = $tupla[admin_mail];

	$ident = $tupla[id];		
	$status = $tupla[status];		
	$sql_aux = mysql_query("SELECT id_sw FROM gets",$dbh);
		
	//$ident recebe o número de paginas ja criadas na swiki (relacionadas na tabela GETS) 
	$query_cont = "SELECT COUNT(*) as CONTADOR from gets where id_sw='$ident'";  
	$sql_cont = mysql_query("$query_cont",$dbh); 	
	$tupla_cont = mysql_fetch_array($sql_cont); 	 	
	$nro_paginas = $tupla_cont[CONTADOR]; 	 
	 		
	$controle=0;
	while (($tupla_aux = mysql_fetch_array($sql_aux)) && ($controle==0)) {
		$comp = $tupla_aux[id_sw];
		if ($ident==$comp) {
			$token=true; 
			$controle=1;
		}
		else $token=false;
		}

	if ($token==true) 
	{
		if ($status == '1') 
                	echo "<LI><a href=\"#\" onClick=\"window.open('login.php?id=$ident&token=1','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">$final</a> ($nro_paginas) p&aacute;gina(s):  (administrador: <A href=\"mailto:$admail\">$admin</A>)"; 
                else
			echo "<LI><a href=\"mostra.php?ident=$ident\" onMouseOver=\"window.status='$final'; return true\" onMouseOut=\"window.status=' '; return true\">$final</a> ($nro_paginas) p&aacute;gina(s):  (administrador: <a href=\"mailto:$admail\">$admin</a>)\n";
        }
	else    
	{
		$final_url = rawurlencode($final);
                if ($status == '1')
                	echo "<LI>$final<a href=\"#\" onClick=\"window.open('login.php?id=$ident&token=0&index=$final_url','login','top=50,left=100,menubar=no,resizable=no,width=300,height=200')\">[create]</a>";
               	else {
			echo "<LI>$final<a href=\"create.php?ident=$ident&index=$final_url\" onmouseover=\"window.status='$final'; return true\" onmouseout=\"window.status=' '; return true\">[create]</a>\n";
		}
        }
}//if
}//while

echo "</UL><BR><B>Total de Entradas:</B>";

	$query_cont = "SELECT COUNT(*) as CONTADOR from swiki where visivel='S'";  
	$sql_cont = mysql_query($query_cont,$dbh); 	
	$tupla_cont = mysql_fetch_array($sql_cont); 	 	
	$nro_swvs = $tupla_cont[CONTADOR]; 	 

	$query_cont = "SELECT COUNT(*) as CONTADOR from swiki where visivel='N'";  
	$sql_cont = mysql_query($query_cont,$dbh); 	
	$tupla_cont = mysql_fetch_array($sql_cont); 	 	
	$nro_swnvs = $tupla_cont[CONTADOR]; 	 

	echo " $nro_swvs [+ $nro_swnvs]";

echo "<BR><B>Total de P&aacute;ginas:</B>";

	$query_cont = "SELECT COUNT(*) as CONTADOR from paginas";  
	$sql_cont = mysql_query($query_cont,$dbh); 	
	$tupla_cont = mysql_fetch_array($sql_cont); 	 	
	$nro_pgs = $tupla_cont[CONTADOR]; 	 

	echo " $nro_pgs<BR>";

?>
<BR>
<!-- Begin Nedstat Basic code -->
<!-- Title: Coteia -->
<!-- URL: http://coweb.icmc.sc.usp.br -->
<script language="JavaScript" src="http://m1.nedstatbasic.net/basic.js">
</script>
<script language="JavaScript">
<!--
  nedstatbasic("ABovXgelWMKjq2QRwNF+X+3Vsxug", 0);
// -->
</script>
<noscript>
<a target="_blank"
href="http://v1.nedstatbasic.net/stats?ABovXgelWMKjq2QRwNF+X+3Vsxug"><img src="http://m1.nedstatbasic.net/n?id=ABovXgelWMKjq2QRwNF+X+3Vsxug"
border="0" nosave width="18" height="18"></a>
</noscript>
<!-- End Nedstat Basic code -->
<BR>
<P>
<HR>
<UL>
<LI><B><A href="help.php" onmouseover="window.status='Ajuda - Coteia'; return true" onmouseout="window.status=' '; return true">Help</A></B>
<BR>
</UL>
</P>
<IMG alt="CoTeia" src="<?echo $URL_IMG?>/logo.gif" border="0">
<BR><I>CoTeia - Ferramenta de Edição Colaborativa Baseada na Web</I>
</BODY>
</HTML>
