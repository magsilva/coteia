<html>
<head><title>ChatServer - CoTeia</title>
<script language="javascript">
function validar() {
                
           // Verifica se o campo username foi preenchido
                        
              if (document.chat.username.value == "") {
                  alert('O campo de apelido é de preenchimento obrigatório!')
                  document.chat.username.value = ""
                  document.chat.username.focus();
                  return false;
              }
}
</script>    
</head>
<body bgcolor="#ffffff" link="#000000" alink="#666666" vlink="#333333" text="#000000" topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<br><br><br>
<center>
<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=330 BGCOLOR=#E1F0FF bordercolor=#C0C0C0 bordercolordark=#C0C0C0 bordercolorlight=#C0C0C0>
<tr><td bgcolor=#0099FF align="center">
<b>Chat Server & CoTeia</b>
</td></tr>
<tr><td align="center">
<form method="POST" action="http://catuaba.icmc.usp.br/chatserver/cs_fs.php" target="_self" onSubmit="return validar();"> 
<input type="hidden" name="session_id" value="<?echo $id?>">
Nome ou Apelido: <input type="text" size="20" name="username"></td></tr>
<tr><td align=center><input type="submit" name="chatserver" value="Entrar"></td></tr>
</form>
</td></tr>
</table>
<center><br><br>
<a href="http://catuaba.icmc.usp.br/chatserver/saved_sessions.php?session_id=<?echo $id?>">Sessões de Chat</a>
</center>
</body></html> 
