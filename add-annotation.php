<html>
<head>
<title>Formul&aacute;rio GroupNote</title>
</head>
<body text="#000000" vLink="#cc0000" aLink="#cccc00" link="#0000ff" bgColor="#ffffff">

<?php
  //echo "iduser: $id_usuario idgroup:$id_grupo<br>";

  include_once("function.inc");  
  
  // No caso de anotacoes internas  
  $array_temp = explode(".", $sw_id);
  $sw_id = $array_temp[0];

  $is_login = annotationLogin($sw_id);

if ($add_an) {
   
  if ($is_login == TRUE) {

      // verifica se o grupo existe
      if (!group_exists($form_grupo))
          msg("Grupo não existe!");

      // autentica o usuario
      $ret = authenticate_user($form_login, $form_pass);
      if ($ret == -1){ // login inválido
          msg("Login inv&aacute;lido!");
      }
      else if ($ret == 0){ // senha inválida
          msg("Senha inv&aacute;lida!");
      }
      else{
          $id_usuario = get_user_id("$form_login");
          $id_grupo   = get_group_id("$form_grupo");

          if (!user_exists_in_group($id_usuario, $id_grupo)){
	      msg("Usuário não pertence ao grupo!");
          }
      } // end else
  } // end if ($is_login)


  $kwd["kwd1"] = $form_kwd1;  
  $kwd["kwd2"] = $form_kwd2;
  $kwd["kwd3"] = $form_kwd3;
//echo "idpasta:$id_pasta, iduser:$id_usuario, idgrupo:$id_grupo";

  $ret = create_annotation ($id_pasta, $id_father, $id_usuario, $id_grupo,"text/xhtml","0","",$form_titulo,$kwd,$annotates,"",$form_texto);

  if ($ret > 0)
      include_once("anotacao.php");
  else
      msg("Erro na criação da anotação!");
	
}
else {
?>
<h2>Anota&ccedil;&atilde;o</h2>
<form name="form_criaAnot" method="post" action="add-annotation.php">
<b>T&iacute;tulo:</b><br>
<input type="text" name="form_titulo" size=30><br>
<b>Conte&uacute;do</b><br>
<textarea name="form_texto" cols=65 rows=10 wrap=virtual>
Insira o conte&uacute;do aqui.
</textarea><br>
<br>
<?php
  if ($is_login == TRUE){
     echo "<b>User:</b> <input type='text' name='form_login' size=8>&nbsp;&nbsp;";
     echo "<b>Grupo:</b> <input type='text' name='form_grupo' size=15>&nbsp;&nbsp;";
     echo "<b>Senha:</b> <input type='password' name='form_pass' size=8>";
   }
?>
<br><br>
<input name="add_an" type="submit" value="enviar"><br>
<input name="canc_an" type="button" value="voltar &agrave; p&aacute;gina anterior" 
onClick="javascript:history.back()">
<input name="close_an" type="button" value="fechar a p&aacute;gina" 
onClick="javascript:window.close()">

<input name="id_pasta" type="hidden" value="<?echo $id_pasta?>">
<input name="id_usuario" type="hidden" value="<?echo $id_usuario?>">
<input name="id_grupo" type="hidden" value="<?echo $id_grupo?>">
<input name="pasta" type="hidden" value="<?echo $pasta?>">
<input name="annotates" type="hidden" value="<?echo $annotates?>">
<input name="id_father" type="hidden" value="<?echo $id_father?>">
<input name="sw_id" type="hidden" value="<?echo $sw_id?>">
</form>

</body>
</html>
<?
}
 
function msg($str){
   echo "<center><b>$str</b><br><a href=javascript:history.back()>Voltar</a></center>";
   echo "</body>";
   echo "</html>";
   exit();
}

?>

