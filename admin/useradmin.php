<?
   include_once("function.inc");

   $sess = new session;

   $sess->read();
?>
<html>
<head>
<?
        include("header.php");
?>
<center>
<table width="600" border="1" cellspacing="0" cellpadding="3" class="box-table">
	<tr>
	<td class="table-header">Login</td>
	<td class="table-header">Email</td>
	<td class="table-header">A&ccedil;&atilde;o</td>
	</tr>
	<tr>
<?
	$dbh = db_connect();

    global $dbname;

        # seleciona base de dados
        mysql_select_db($dbname,$dbh);

        $sql = mysql_query("SELECT id,login,email FROM admin",$dbh);

        while ($tupla = mysql_fetch_array($sql)) {
        $id = $tupla["id"];
        $username = $tupla["login"];
        $email = $tupla["email"];
        
	echo "<tr>
	<td valign=\"middle\">$username</td>
	<td valign=\"middle\">$email</td>";

	if ($sess_val == "admin") {
	echo "<td valign=\"middle\"><a href=\"edituser.php?id=$id\">editar</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"userfcao.php?id=$id&amp;par=2\">apagar</a></td>";
	}
	else {
	echo "<td valign=\"middle\">editar&nbsp;&nbsp;|&nbsp;&nbsp;apagar</td>";
	}
	echo "</td>
	</tr>";
    }
?>
        <tr>
        <td valign="middle" colspan="3" class="table-header">
<?
	if ($sess_val == "admin") {
	echo "<a href=\"adduser.php\"><font color=\"white\">Adicionar Usu&aacute;rio</font></a></td>";
	}
	else {
	echo "<font color=\"white\">Adicionar Usu&aacute;rio</font></td>";
	}
?>
        </tr>
        <tr>
        <td valign="middle" colspan="3" class="table-footer">
        <a href="main.php">Menu Principal</a></td>
        </tr>
	</table></center><br>
<?
        include("footer.php");
?>
