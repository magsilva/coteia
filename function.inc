<?php

include_once( "config.php.inc" )

/*
 * NOME:		anota_db_pconnect
 * DESCRICAO:		Realiza a conexao da anotacao com o DBMS MySQL 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function anota_db_pconnect() {

        global $anota_dbhost, $anota_dbuser, $anota_dbpword;

        return(mysql_pconnect($anota_dbhost, $anota_dbuser, $anota_dbpword));

}

/*
 * NOME:		user_db_pconnect
 * DESCRICAO:		Realiza a conexao de usuarios com o DBMS MySQL 
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function user_db_pconnect() {

        global $user_dbhost, $user_dbuser, $user_dbpword;

        return(mysql_pconnect($user_dbhost, $user_dbuser, $user_dbpword));

}

/*
 * NOME:		user_id_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function user_id_exists($id,$dbh)  {
    
    global $user_dbname;

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT * FROM t_user WHERE id=$id";
    $resultado = mysql_query($comando_sql);
   
    if (mysql_num_rows($resultado))
      return 1;
    
    return 0;
    
}

/*
 * NOME:		group_id_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function group_id_exists($id,$dbh)  {
    
    global $user_dbname;

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT * FROM t_group WHERE id=$id";
    $resultado = mysql_query($comando_sql);
   
    if (mysql_num_rows($resultado))
      return 1;
    
    return 0;
    
}

/*
 * NOME:		get_user_name
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_user_name($id,$dbh) {
  
    global $user_dbname;
    if ($dbh == "")
         $dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $dbh);
    
    $comando_sql = "SELECT name FROM t_user WHERE id=$id";
    $resultado = mysql_query($comando_sql);
    //echo $comando_sql;
    return mysql_result($resultado, 0, 'name');
    
} 

/*
 * NOME:		get_group_name
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_group_name($id,$dbh) {
    
    global $user_dbname;
    if ($dbh == "")
         $dbh = user_db_pconnect();

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT name FROM t_group WHERE id=$id";
    $resultado = mysql_query($comando_sql);
    $name = mysql_result($resultado, 0, name);

    return mysql_result($resultado, 0, 'name');
    
}

/*
 * NOME:		relates_annotation_with_folder
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function relates_annotation_with_folder($id_folder, $id_annotation,$dbh)  {
    // Verifica entrada
    $id_folder     = (int)$id_folder;
    $id_annotation = (int)$id_annotation;
    if (!$id_folder || !$id_annotation)
        return -1;
  
    // Verifica se folder e annotation existem
    if (!folder_exists($id_folder,$dbh))
       return -3;
    if (!annotation_exists($id_annotation,$dbh))
       return -2;
 
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
    
    // Tenta inserir uma tupla na tabela "rel_annotation_folder"
    $comando_sql = "INSERT INTO rel_annotation_folder VALUES ($id_folder, $id_annotation, NOW())";
    $resultado = mysql_query($comando_sql);
    
    if (!mysql_affected_rows($dbh))
         return 0; // erro na insercao   
    return 1;
        
}

/*
 * NOME:		get_annotation_children
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotation_children($id_father)  {
    // Verifica entrada
    $id_father = (int)$id_father;
    if (!$id_father)
       return -1;
                       
    global $anota_dbname;

    $dbh = anota_db_pconnect();

    $select_db = mysql_select_db($anota_dbname, $dbh);

    // Verifica se annotation existe
    if (!annotation_exists($id_father,$dbh))
       return 0;
      
    $comando_sql = "SELECT id_main, level, id_father, id, title FROM annotation WHERE id_father = $id_father ORDER BY title";
     
    $resultado = mysql_query($comando_sql);
    $array = array();
    $array[0] = mysql_num_rows($resultado);   
   
    $i = 1;
    while ($linha = mysql_fetch_array($resultado)){
      $array[$i]["id_main"]   = mysql_result($resultado, $i-1, "id_main");
      $array[$i]["level"]     = mysql_result($resultado, $i-1, "level");
      $array[$i]["id_father"] = mysql_result($resultado, $i-1, "id_father");
      $array[$i]["id"] = mysql_result($resultado, $i-1, "id");
      $array[$i]["title"] = mysql_result($resultado, $i-1, "title");
      $i++;
    }
    
    mysql_free_result($resultado);  
    
    return $array;
       
} 

/*
 * NOME:		get_annotations_level_zero_annotates
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotations_level_zero_annotates($annotates)  {
    // Verifica entrada
    if ($annotates == "")
       return -1;
        
    global $anota_dbname;

    $dbh = anota_db_pconnect();

    $select_db = mysql_select_db($anota_dbname, $dbh);
        
    $comando_sql = "SELECT id_main, level, id_father, id, title FROM annotation WHERE annotates = '$annotates' AND level = 0 ORDER BY creation_date";
    $resultado = mysql_query($comando_sql);
    $array = array();
    $array[0] = mysql_num_rows($resultado);
    $i = 1;         
    while ($row = mysql_fetch_array($resultado))
    {
        $array[$i]["id_main"] = mysql_result($resultado, $i-1, "id_main");
        $array[$i]["level"] = mysql_result($resultado, $i-1, "level");
        $array[$i]["id_father"] = mysql_result($resultado, $i-1, "id_father");
        $array[$i]["id"] = mysql_result($resultado, $i-1, "id");
        $array[$i]["title"] = mysql_result($resultado, $i-1, "title");
        $i++;
    }
                    
    mysql_free_result($resultado);

    return $array;
} 

/*
 * NOME:		annotation_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function annotation_exists($id,$dbh)
  {
    // Verifica entrada
    $id = (int)$id;
    if (!$id)
       return -1;

    global $user_dbname;

    $select_db = mysql_select_db($user_dbname, $dbh);

    $comando_sql = "SELECT * FROM annotation WHERE id=$id";
    $resultado = mysql_query($comando_sql,$dbh);
    
    if (!mysql_num_rows($resultado))
      return 0;
    else{
      mysql_free_result($resultado);
      return 1;
    }
}

/*
 * NOME:		folder_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function folder_exists($id,$dbh) { 
    // Verifica entrada
    $id = (int)$id;
    if (!$id)
       return -1;

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
      
    $comando_sql = "SELECT * FROM folder WHERE id=$id";
    $resultado = mysql_query($comando_sql,$dbh);

    if (!mysql_num_rows($resultado))
       return 0;
    else{
      mysql_free_result($resultado);
      return 1;
    }
    
} 

/*
 * NOME:		get_annotation_main
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotation_main ($id_annotation,$dbh)
  { 
    // Verifica entrada
    $id_annotation = (int)$id_annotation;
    if (!$id_annotation)
       return -1;
  
    global $anota_dbname;
 
    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT id_main FROM annotation WHERE id=$id_annotation";
    $resultado = mysql_query($comando_sql,$dbh);
    
    if (!mysql_affected_rows($dbh))
         return 0; // erro provavel: anotacao inexistente
  
    return mysql_result($resultado, 0, "id_main");
}

/*
 * NOME:		get_annotation_level
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotation_level($id_annotation,$dbh)
  { 
    // Verifica entrada
    $id_annotation = (int)$id_annotation;
    if (!$id_annotation)
       return -1;
  
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT level FROM annotation WHERE id=$id_annotation";
    $resultado = mysql_query($comando_sql,$dbh);
    
    if (!mysql_affected_rows($dbh))
         return -2; // erro provavel: anotacao inexistente
    
    return mysql_result($resultado, 0, "level");
}

/*
 * NOME:		create_annotation
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function create_annotation($id_folder,$id_father,$id_owner,$id_group,$format,$access_permission,$type,$title,$kwd,$annotates,$context,$body)  {

    global $anota_dbname;

    $dbh = anota_db_pconnect();

    # seleciona base de dados
    mysql_select_db($anota_dbname,$dbh);
    
    $id_folder = (int)$id_folder;
    $id_father = (int)$id_father;
    $id_owner  = (int)$id_owner;
    $id_group  = (int)$id_group;
      
    // Verifica entrada:
    if (!$id_folder || !$id_owner || !$id_group)
       return -1;
      
    // Verifica se o usuário pode criar uma anotação na pasta especificada
    $aux = test_user_can_writes_folder($id_folder, $id_owner, $id_group,$dbh);
    if ($aux == -2) // id user inexistente
       return -2;
    else if ($aux == -3) // id group inexistente
	return -3;
    else if ($aux == -4) // erro provavel: pasta inexistente
       return -4;
    else if (!$aux) // usuario nao pode criar anotacao
       return 0;
    
    // Procura o nivel da anotacao a ser inserida
    // Verifica se a futura anotação possui pai
    if (!$id_father) // não possui pai
      $level = 0;
    else{ // possui pai 
      // Procura o nível da pasta pai   

      $level = get_annotation_level($id_father,$dbh);
      if ($level == -2) // erro provavel: anotacao pai inexistente
         return -5; 
      $level = $level + 1;
      // Procura o main da pasta pai
      
      $id_main = get_annotation_main($id_father,$dbh);
    }
    
    $id_main = (int)$id_main;
       
    if (is_array($access_permission)){
       $access_permission = transform_accesspermission($access_permission);
    }
    
    // Trata as strings
 
    $str_trat = tratamento(0,$body,$title,$type,$kwd);
    
    $comando_sql = "INSERT INTO annotation VALUES (NULL, $id_main, $id_father, $level, '$format', '$str_trat[author]', '$str_trat[title]','$str_trat[key1]', '$str_trat[key2]', '$str_trat[key3]', $id_owner, $id_group, NOW(), $id_owner,$id_group, NOW(), '$annotates', '$context', $access_permission, '$str_trat[content]', '', 1)";
    mysql_query($comando_sql,$dbh);

    if (!mysql_affected_rows($dbh))
       return -6; // erro na insercao
    
    $id = mysql_insert_id($dbh);
    
    if (!$id_main){ 
      $comando_sql = "UPDATE annotation SET id_main=$id WHERE id = $id";
      $resultado   = mysql_query($comando_sql,$dbh);
      if (!mysql_affected_rows($dbh)){ // erro na atualizacao
         // Apaga a anotacao inserida
         $comando_sql = "DELETE FROM annotation WHERE id=$id";
         mysql_query($comando_sql,$dbh);
         return -6;
      }
    }
    
    // Relaciona a anotação criada com a pasta especificada
    

    if (!relates_annotation_with_folder($id_folder,$id,$dbh)){
       // Apaga a anotacao inserida
	$comando_sql = "DELETE FROM annotation WHERE id=$id";
        mysql_query($comando_sql,$dbh);
        return -6;
      }
       
    $comando_sql = "INSERT INTO annotation_history VALUES ($id, $id_owner, $id_group, 1, '$msg', NOW())";       $resultado = mysql_query($comando_sql);
     
    return $id;  
} 

/*
 * NOME:		get_annotation_attributes
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotation_attributes ($id_annotation) {

    // Verifica entrada
    $id_annotation = (int) $id_annotation;
    if (!$id_annotation)
       return -1;
    
    global $anota_dbname;

    $dbh = anota_db_pconnect();

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT title, type, format, kwd1, kwd2, kwd3, id_main, id_father, level,id_owner, id_group, creation_date, id_last_user, id_last_group,last_modified, access_permission, context, annotates, body,version FROM annotation WHERE id=$id_annotation";
    $resultado = mysql_query($comando_sql);

    if (!mysql_affected_rows($dbh))
       return 0;
     
    $properties["title"] = mysql_result($resultado, 0, "title");
    $properties["type"] = mysql_result($resultado, 0, "type");
    $properties["format"] = mysql_result($resultado, 0, "format");
    $properties["kwd1"] = mysql_result($resultado, 0, "kwd1");
    $properties["kwd2"] = mysql_result($resultado, 0, "kwd2");
    $properties["kwd3"] = mysql_result($resultado, 0, "kwd3");
    $properties["id_main"] = mysql_result($resultado, 0, "id_main");
    $properties["id_father"] = mysql_result($resultado, 0, "id_father");
    $properties["level"] = mysql_result($resultado, 0, "level");
    $properties["id_owner"] = mysql_result($resultado, 0, "id_owner");
    $properties["id_group"] = mysql_result($resultado, 0, "id_group");
    $properties["creation_date"] = mysql_result($resultado, 0, "creation_date");
    $properties["id_last_user"] = mysql_result($resultado, 0, "id_last_user");
    $properties["id_last_group"] = mysql_result($resultado, 0, "id_last_group");
    $properties["last_modified"] = mysql_result($resultado, 0, "last_modified");
    $properties["access_permission"] = mysql_result($resultado, 0, "access_permission");  
    $properties["context"] = mysql_result($resultado, 0, "context");
    $properties["annotates"] = mysql_result($resultado, 0, "annotates");
    $properties["body"] = mysql_result($resultado, 0, "body");
    $properties["version"] = mysql_result($resultado, 0, "version");

    return $properties;
}

/*
 * NOME:		get_annotation_xml
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_annotation_xml($id_annotation)   {

    global $anota_dbname;

    $dbh = anota_db_pconnect();

    # seleciona base de dados
    mysql_select_db($anota_dbname,$dbh);
    
      // Verifica entrada
      $id_annotation = (int)$id_annotation;
      if (!$id_annotation)
         return -1;
      
      $annotation_attributes = get_annotation_attributes($id_annotation);
      if (!$annotation_attributes)
		return -2; // anotacao inexistente
      
    $user_dbh = user_db_pconnect();

      $annotation_attributes["group"] = get_group_name($annotation_attributes["id_group"],$user_dbh);
      $annotation_attributes["last_group"] = get_group_name($annotation_attributes["id_last_group"],$user_dbh);
     
      $annotation_attributes["owner"] = get_user_name($annotation_attributes["id_owner"],$user_dbh);
      $annotation_attributes["last_user"] = get_user_name($annotation_attributes["id_last_user"],$user_dbh);
      
      // Verifica as permissoes de acesso
      $acesso = $annotation_attributes["access_permission"];
      $annotation_attributes["permission_owner"] = "none";
      $annotation_attributes["permission_group"] = "none";
      $annotation_attributes["permission_other"] = "none";
      $annotation_attributes["permission_guest"] = "none";
      
      if ($acesso & 128)
        $annotation_attributes["permission_owner"] = "r";
      if ($acesso & 64)
        $annotation_attributes["permission_owner"] = $annotation_attributes["permission_owner"]."w";
      if ($acesso & 32)
        $annotation_attributes["permission_group"] = "r";
      if ($acesso & 16)
	 $annotation_attributes["permission_group"] = $annotation_attributes["permission_group"]."w";
      if ($acesso & 8)
        $annotation_attributes["permission_other"] = "r";
      if ($acesso & 4)
        $annotation_attributes["permission_other"] = $annotation_attributes["permission_other"]."w";
      if ($acesso & 2)
        $annotation_attributes["permission_guest"] = "r";
      if ($acesso & 1)
        $annotation_attributes["permission_guest"] = $annotation_attributes["permission_guest"]."w";

	$string_XML =
      '<?xml version="1.0" encoding="ISO-8859-1"?>
      <an:annotation xmlns:dc="http://purl.org/metadata/dublin_core#"
                     xmlns:a="http://www.w3.org/2000/10/annotation-ns#"
                     xmlns:an="http://coweb.icmc.sc.usp.br/GroupNote-ns#"
                     id="$id_annotation" dc:format="text/xhtml">
        <dc:title>'.encode($annotation_attributes[title]).'</dc:title>';
        if ($annotation_attributes["type"])
           $string_XML =  $string_XML.'
        <dc:type>'.encode($annotation_attributes[type]).'</dc:type>';
        $i = 1;
        while ($annotation_attributes["kwd"."$i"] != ""){
           $string_XML = $string_XML.'
        <dc:subject>'.encode($annotation_attributes["kwd"."$i"]).'</dc:subject>';
           $i++;
        }
        $string_XML = $string_XML."
        <an:owner>$annotation_attributes[owner]</an:owner>
        <an:group>$annotation_attributes[group]</an:group>
        <an:creation_date>$annotation_attributes[creation_date]</an:creation_date>
        <an:last_user>$annotation_attributes[last_user]</an:last_user>
        <an:last_group>$annotation_attributes[last_group]</an:last_group>
	<an:last_modified>$annotation_attributes[last_modified]</an:last_modified>";
        if ($annotation_attributes["annotates"] != "")
           $string_XML = $string_XML."
        <a:annotates>$annotation_attributes[annotates]</a:annotates>";
        if ($annotation_attributes["context"] != "")
           $string_XML = $string_XML."
        <a:context>$annotation_attributes[context]</a:context>";
        $string_XML = $string_XML.'
        <an:owner_access_permission>'.$annotation_attributes["permission_owner"].'</an:owner_access_permission>
        <an:group_access_permission>'.$annotation_attributes["permission_group"].'</an:group_access_permission>
        <an:other_access_permission>'.$annotation_attributes["permission_other"].'</an:other_access_permission>
        <an:guest_access_permission>'.$annotation_attributes["permission_guest"].'</an:guest_access_permission>';
        $string_XML = $string_XML."
        <body>
        $annotation_attributes[body]
        </body>
      </an:annotation>";

       return $string_XML;
} 

/*
 * NOME:		get_folder_attributes
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_attributes($id_folder,$dbh)  {
    // Verifica entrada
    $id_folder = (int) $id_folder;
    if (!$id_folder)
       return -1;
    
    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);

    $comando_sql = "SELECT name, id_main, id_father, level, id_owner, id_group, creation_date,id_last_user, id_last_group, last_modified, access_permission FROM folder WHERE id=$id_folder";
    $resultado = mysql_query($comando_sql,$dbh);
	
    if (!mysql_affected_rows($dbh))
       return 0;
  
    $properties["name"] = mysql_result($resultado, 0, "name");
    $properties["id_main"] = mysql_result($resultado, 0, "id_main");
    $properties["id_father"] = mysql_result($resultado, 0, "id_father");
    $properties["level"] = mysql_result($resultado, 0, "level");
    $properties["id_owner"] = mysql_result($resultado, 0, "id_owner");
    $properties["id_group"] = mysql_result($resultado, 0, "id_group");
    $properties["creation_date"] = mysql_result($resultado, 0, "creation_date");
    $properties["id_last_user"] = mysql_result($resultado, 0, "id_last_user");
    $properties["id_last_group"] = mysql_result($resultado, 0, "id_last_group");
    $properties["last_modified"] = mysql_result($resultado, 0, "last_modified");
    $properties["access_permission"] = mysql_result($resultado, 0, "access_permission");
    
    return $properties;
}

/*
 * NOME:		get_folder_father
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_father($id_folder,$dbh) {
  // Verifica a entrada
  $id_folder = (int)$id_folder;
  if (!$id_folder)
     return -1;  

    global $anota_dbname;

    $select_db = mysql_select_db($anota_dbname, $dbh);
     
  $comando_sql = "SELECT id_father FROM folder WHERE id = $id_folder";
  $resultado = mysql_query($comando_sql,$dbh);
  
  if (!mysql_affected_rows($dbh)) // erro provavel: pasta inexistente
       return -2;
     
  return mysql_result($resultado, 0, "id_father");
} 

/*
 * NOME:		get_folder_ancestral
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function get_folder_ancestral($id_folder,$dbh) {
  // Verifica a entrada
  $id_folder = (int) $id_folder;
  if (!$id_folder)
     return -1;
  
  $father = get_folder_father($id_folder,$dbh);
  if ($father == -2) // erro provavel: pasta inexistente
     return 0;
     
  $array[0] = $father;  
  $i = 1;
  while ($father != 0) {   
  	$father = get_folder_father($father,$dbh);
  	if ($father == -2) // erro provavel: pasta inexistente
       		return 0;
    	$array[$i] = $father;
    	$i++;
  }
     
  return $array;
}

/*

/*
 * NOME:		test_userclass_can_writes_folder_only
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function test_userclass_can_writes_folder_only ($id_folder, $user_class,$dbh)  {  

    // Verifica a entrada   
    $id_folder = (int) $id_folder;
    if (!$id_folder || !($user_class == "owner")&& !($user_class == "group")&& !($user_class == "other") && !($user_class == "guest"))
       return -1;
    
    $properties = get_folder_attributes($id_folder,$dbh);
    
    if (!$properties) // erro provavel: pasta inexistente
       return -2;
    
    switch ($user_class){
       case "owner":
           if (!($properties["access_permission"] & 64)){
             return 0;
           }
           break;   
     case "group":
           if (!($properties["access_permission"] & 16)){
             return 0;
           }
           break;
       default: // other
           if (!($properties["access_permission"] & 4)){
             return 0;
           }
    } // fim switch
    return 1;
}

/*
 * NOME:		test_user_can_writes_folder
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function test_user_can_writes_folder($id_folder, $id_user, $id_group,$dbh) {
    // Verifica a entrada
    $id_folder = (int)$id_folder;
    $id_user   = (int)$id_user;   
    $id_group  = (int)$id_group;
    if ((!$id_folder)||(!$id_user)||(!$id_group))
       return -1;
     
    // Verifica se id_user e id_group existem
    $user_dbh = user_db_pconnect();

    if (!user_id_exists($id_user,$user_dbh))
       return -2;

    if (!group_id_exists($id_group,$user_dbh))
       return -3;
  
    // Procura id_owner e id_group da pasta

    $properties = get_folder_attributes($id_folder,$dbh);
    if (!$properties) // erro provavel: pasta inexistente
       return -4;
    
    if ($properties["id_owner"] == $id_user)
         $user_class = "owner";
    else if ($properties["id_group"] == $id_group)
         $user_class = "group";
    else if ($id_user == 0) // é guest. Guest nunca pode escrever
         return 0;
    else
         $user_class = "other";
    
    // 3 - Caso a permissao de escrita daquela pasta para o usuario tenha sido positiva,
    //     pode ser que os ancestrais diretos da pasta nao tenha

    $ancestral = get_folder_ancestral($id_folder,$dbh);
    if (!$ancestral) // erro provavel: pasta inexistente
        return -4;
     
    $i = 0;  
    reset($ancestral);
    while(current($ancestral)){ // para cada ancestral verifica se a pasta possui permissao de escrita

      $aux = test_userclass_can_writes_folder_only($ancestral[$i], $user_class,$dbh);
      if ($aux == -1) // entrada invalida
         return -1;
      else if ($aux == -2) //erro provavel: pasta inexistente
         return -4;
      else if (!$aux) // usuario nao possui permissao
        return 0;
	next($ancestral);
      $i++;
    }
    return 1;
    
}   


/*
 * NOME:		transform_accesspermission
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function transform_accesspermission($array)  {
    if (!is_array($array))
        return -1;
  
    $access_permission = 0;
  
    if ($array["guest_write"])
         $access_permission += 1;
  
    if ($array["guest_read"])
        $access_permission += 2;
  
    if ($array["other_write"])
         $access_permission += 4;
    
    if ($array["other_read"])
         $access_permission += 8;

    if ($array["group_write"])
         $access_permission += 16;
     
    if ($array["group_read"])
         $access_permission += 32;
 
    if ($array["owner_write"])
         $access_permission += 64;
    
    if ($array["owner_read"])
         $access_permission += 128;
    
    return $access_permission;
    
} 

/*
 * NOME:		db_connect
 * DESCRICAO:		Realiza a conexao com o DBMS MySQL
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function db_connect(){

	global $dbhost, $dbuser, $dbpass;

	return(mysql_connect($dbhost, $dbuser, $dbpass));

}

/*
 * NOME:		autentica
 * DESCRICAO:		Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:	$usuario - usuario & $passwd - senha & $dbh - handler do banco
 * PAR. SAIDA:		--
 * RETORNO:		TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:		--
 */
function autentica($usuario,$senha,$dbh){
   
      global $dbname;

      # seleciona base de dados
      mysql_select_db($dbname,$dbh);

      $query = mysql_query("select login,email from admin where login='$usuario' AND pass= PASSWORD('$senha')");
      # verifica se o usuário é valido
      if (mysql_num_rows($query) > 0) {
 
            $result  = mysql_fetch_array($query); 
            $usuario = $result["login"];
            $usermail = $result["email"];
 
            session_start(); //Inicializa a sessão
            session_register("usuario","usermail"); //Registra as variáveis na sessão
 
            return true;
     }
     else return false;

}               

/*
 * NOME:		login_swiki
 * DESCRICAO:		
 * PAR. ENTRADA:	$usuario - usuario & $passwd - senha & $dbh - handler do banco
 * PAR. SAIDA:		--
 * RETORNO:		TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:		--
 */
function login_swiki($usuario,$passwd,$id,$dbh) {

global $dbname;

//sql que verifica se o usuário está cadastrado
$sql  = "SELECT id,username FROM swiki WHERE username='$usuario' AND password=md5('$passwd') AND id='$id'";

$select_db = mysql_select_db($dbname,$dbh);

//executa o sql
$query = mysql_query($sql);

//verifica se o usuário é valido
if (mysql_num_rows($query) > 0) {

	$result  = mysql_fetch_array($query);
        $coduser = $result["id"];
        $namuser = $result["username"];
                
        session_start("login"); //Inicializa a sessão
        session_name();
        session_destroy();
        session_register("namuser","coduser"); //Registra as variáveis na sessão
                
	return true;
        } else {
	echo '<br><center><font face="Verdana" color="#000000" size="+2">Área Restrita.</font><br><br>
        <a href="javascript:window.close()">Fechar Janela</a></center><br>';
	} 
}

/*
 * NOME:		verifica_session
 * DESCRICAO:		Verifica se as variaveis de sessao estao ativas
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Continua em caso de sucesso, Msg de erro e saida do script em caso de erro
 * OBSERVACOES:		
 */
function verifica_session() {

	session_start();

	if (!(session_is_registered("usuario") AND session_is_registered("usermail"))) {
	header("Location:acessoilegal.php"); //Redireciona para interface de acesso ilegal
	exit;
	}

}

/*
 * NOME:		logout
 * DESCRICAO:		destroi variaveis de sessao
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
 */
function logout() {

session_start();
session_unregister("usuario");
session_unregister("usermail");

}

/*
 * NOME:		replace
 * DESCRICAO:		converte tags para minusculo(XHTML), cria tags de linha e converte caracteres especias em entidades HTML
 * PAR. ENTRADA:        texto
 * PAR. SAIDA:		texto traduzido em entidades e XHTML
 * RETORNO:		--
 * OBSERVACOES:
 */
function replace($texto) {

	$open_bold = eregi_replace("<b>","<b>",$texto);
	$close_bold = eregi_replace("</b>","</b>","$open_bold");

	$open_italic = eregi_replace("<i>","<i>",$close_bold);
	$close_italic = eregi_replace("</i>","</i>",$open_italic);

	$open_ul = eregi_replace("<ul>","<ul>",$close_italic);
	$close_ul = eregi_replace("</ul>","</ul>",$open_ul);

	$open_ol = eregi_replace("<ol>","<ol>",$close_ul);
	$close_ol = eregi_replace("</ol>","</ol>",$open_ol);

	$open_li = eregi_replace("<li>","<li>",$close_ol);
	$close_li = eregi_replace("</li>","</li>",$open_li);

	$h1 = eregi_replace("<h1>","<h1>",$close_li);
	$h1 = eregi_replace("</h1>","</h1>",$h1);
	$h2 = eregi_replace("<h2>","<h2>",$h1);
	$h2 = eregi_replace("</h2>","</h2>",$h2);
	$h3 = eregi_replace("<h3>","<h3>",$h2);
	$h3 = eregi_replace("</h3>","</h3>",$h3);

	$img = eregi_replace("<img","<img",$h3);

	$open_font = eregi_replace("<font","<font",$img);
	$close_font = eregi_replace("</font>","</font>",$open_font);

        $open_table = eregi_replace("<table","<table",$open_font);
        $close_table = eregi_replace("</table>","</table>",$open_table);
        
        $open_td = eregi_replace("<td>","<td>",$close_table);
        $close_td = eregi_replace("</td>","</td>",$open_td);
        
        $open_tr = eregi_replace("<tr>","<tr>",$close_td);
        $close_tr = eregi_replace("</tr>","</tr>",$open_tr);
        
        $open_pre = eregi_replace("<pre>","<pre>",$close_tr);
        $close_pre = eregi_replace("</pre>","</pre>",$open_pre);

        $open_center = eregi_replace("<center>","<center>",$close_pre);
	$close_center = eregi_replace("</center>","</center>",$open_center);

	$open_link = eregi_replace("<lnk>","<lnk>",$close_center);
	$close_link = eregi_replace("</lnk>","</lnk>",$open_link);

        $file_upl = eregi_replace("<upl FILE","<upl file",$close_link);
        $close_upl = eregi_replace("</upl>","</upl>",$file_upl);

        $agenda = eregi_replace("<agenda","<agenda",$close_upl);

	$open_href = eregi_replace("<a href","<a href",$agenda);
	$close_href = eregi_replace("</a>","</a>",$open_href);

	$string1 = nl2br($close_href);
	$string2 = eregi_replace ("<br>","<br/>",$string1);
      
	$trans = get_html_translation_table (HTML_ENTITIES);
	$encoded = strtr ($string2, $trans);

	$texto_single_quot = eregi_replace("'","`",$encoded);
	$texto_quot = eregi_replace("&quot;","\"",$texto_single_quot);
        $texto_aux = eregi_replace("&lt;","<",$texto_quot);
        $texto_final = eregi_replace("&gt;",">",$texto_aux);
        
        return $texto_final;

}//function replace

/*
 * NOME:		table_pre
 * DESCRICAO:		
 * PAR. ENTRADA:        texto
 * PAR. SAIDA:		
 * RETORNO:		--
 * OBSERVACOES:
 */
function table_pre($conteudo,$tipo) {
  
$continua=true;
$texto = " ".$conteudo;
$p1_ini = "0";
 
if ($tipo == "table")
$tam_tipo = "8";
else if ($tipo == "pre")
$tam_tipo = "6";
else $tam_tipo = "5";

while ($continua == true)
        {
        $texto_pos_ini = strpos($texto,"<$tipo");
        if (!$texto_pos_ini)
                {
                $continua=false ;
                } else
                {
                $antes_tabela = substr($texto,$p1_ini,$texto_pos_ini);
                $texto_pos_fin = strpos($texto,"</$tipo>") ;
                $texto_fim = $texto_pos_fin + $tam_tipo;
                $texto_tam = $texto_fim - $texto_pos_ini;
                $str = substr($texto,$texto_pos_ini,$texto_tam);
                $str = eregi_replace ("<br/>","",$str); 
                $str = eregi_replace ("<br />","",$str); 
                $str = eregi_replace ("<$tipo","<aux",$str); 
                $str = eregi_replace ("</$tipo>","</aux>",$str);

		//pre-formatado: nao faz parsing e tags sao mostradas na tela
		if ($tipo == "pre") {
			$str = eregi_replace ("<","&lt;",$str); 
			$str = eregi_replace (">","&gt;",$str); 

			$str = eregi_replace ("&lt;aux&gt;","<aux>",$str); 
			$str = eregi_replace ("&lt;/aux&gt;","</aux>",$str); 
                }

                $depois_tabela = substr($texto,$texto_fim); 
                $texto = $antes_tabela.$str.$depois_tabela;
                }
}

        $conteudo = eregi_replace ("<aux","<$tipo",$texto);
        $conteudo = eregi_replace ("</aux>","</$tipo>",$conteudo);
                        
        return trim($conteudo);
                        
}//function 

/*
 * NOME:		encode
 * DESCRICAO:		converte texto em entidades HTML
 * PAR. ENTRADA:        texto
 * PAR. SAIDA:		texto traduzido em entidades e XHTML
 * RETORNO:		--
 * OBSERVACOES:
*/
function encode($texto) {

	$trans = get_html_translation_table (HTML_ENTITIES);
	$encoded = strtr ($texto, $trans);

	return $encoded;

}//function encode

/*
 * NOME:		tratamento
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function tratamento($index,$conteudo,$titulo,$autor,$kwd) {

		trim($conteudo);  //elimina espaços em branco no começo e fim da variável
		trim($titulo);    //elimina espaços em branco no começo e fim da variável

		$conteudo = stripslashes($conteudo);

		$texto_final = replace($conteudo);

		$index_trans = encode($index);
		$titulo_trans = encode($titulo);
		$autor_trans = encode($autor);

		$count = count($kwd);
		for ($i=1;$i <= $count;$i++) {
			$key[$i] = encode($kwd[$i]);
		}

		$trat["index"] = $index_trans;
		$trat["content"] = $texto_final;
		$trat["title"] = $titulo_trans;
		$trat["author"] = $autor_trans;

		for ($i=1;$i <= $count;$i++) {
                        $trat["key"."$i"] = $key[$i];
                }

		return $trat;

}//function tratamento

/*
 * NOME:		arq
 * DESCRICAO:		aplicacao XML/XSL(via XT) e manipulacao de arquivos.
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:         --
*/
function arq($id,$xmlfile,$arq_xsl,$cp_xt,$cp_java,$path_html,$path_xml) {

$arq_xml = tempnam("$path_xml","coweb");
chmod("$arq_xml",0755);
$fp = fopen("$arq_xml","w+");
fputs($fp,$xmlfile);
fclose ($fp);

//$cp_java = classpath para executar java.
//Ex. de $cp_java =  "/usr/local/jdk1.2.2/bin/java"
//$cp_xt   = classpath para os arquivos: xt.jar, sax.jar e xp.jar
//Ex. de $cp_xt = "/home/xt/xt.jar:/home/xt/sax.jar:/home/xp/xp.jar"
//Uso do XT = [path_java] -classpath [classpath_xt] com.jclark.xsl.sax.Driver [xml] [xsl] [html]

exec("$cp_java -classpath $cp_xt com.jclark.xsl.sax.Driver $arq_xml $arq_xsl $path_html/$id.html",$var_array,$status);
//For Win: exec("$cp_java $arq_xml $arq_xsl $path_html/$id.html",$var_array,$status);

return $status;

}//function file

/*
 * NOME:		xml_xsl
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function xml_xsl($id,$conteudo,$titulo,$autor,$key,$arq_xsl,$cp_xt,$cp_java,$path_html,$path_xml,$dtd,$node,$ident,$lock,$annotation,$chat,$eclass,$others,$linksto_id,$linksto_titulo,$kwd,$aut,$tit,$body) {

	trim($conteudo);  //elimina espaços em branco no começo e fim da variável
	trim($titulo);    //elimina espaços em branco no começo e fim da variável
  
	//resolve o problema do sinal de menor e maior - conflito com elementos XML
	//para aceitar estas entidades tem que colocar na textarea como entidades
        $conteudo = eregi_replace("&amp;lt;","&lt;",$conteudo);
        $conteudo = eregi_replace("&amp;gt;","&gt;",$conteudo);

	//resolve o problema da entidade de branco- conflito com elementos XML
        $conteudo = eregi_replace("&amp;nbsp;","&nbsp;",$conteudo);

	$titulo = eregi_replace("&amp;lt;","&lt;",$titulo);
        $titulo = eregi_replace("&amp;gt;","&gt;",$titulo);

	$count = count($key);
	$i = 1;

	while($i <= $count) {
        	$keyword = $keyword."<$kwd[$i]>$key[$i]</$kwd[$i]>";
		$i++;
        }

	if ($linksto_id[1]  == "0") { 
	$links = $links."<ref id=\"index.php\">$linksto_titulo[1]</ref>";
		for ($i=2;$i <= count($linksto_id);$i++) {
			$links = $links."<ref id=\"mostra.php?ident=$linksto_id[$i]\">$linksto_titulo[$i]</ref>";
		}
	} else {
		for ($i=1;$i <= count($linksto_id);$i++) {
			$links = $links."<ref id=\"mostra.php?ident=$linksto_id[$i]\">$linksto_titulo[$i]</ref>";
		}
	}

$xmlfile =  "<?xml version='1.0'?>
$dtd
<$node>
<$ident>$id</$ident>
$lock
$annotation
$chat
$eclass
$others
$links
$keyword
<$aut>$autor</$aut>
<$tit>$titulo</$tit>
<$body>$conteudo</$body>
</$node>";
	
$feedback = (arq($id,$xmlfile,$arq_xsl,$cp_xt,$cp_java,$path_html,$path_xml));

if ($feedback==0) {
	return TRUE;
	}
	else {
	return FALSE;
	}

}//function xml_xsl

/*
 * NOME:		img_upload
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function img_upload($conteudo) {

$continua=true;
$texto = " ".$conteudo;
$n=0;

  while ($continua == true)
        {
        $texto_pos_ini = strpos($texto,"<upl");
	if (!$texto_pos_ini)
                {
                $continua=false;
                } else
                        {
                        $texto_pos_fin = strpos($texto,"</upl>");
                        $texto_final = $texto_pos_fin + 6;
			$tam_upl = $texto_final - $texto_pos_ini;
			$texto_aux[$n] = substr($texto,0,$texto_pos_ini);
			$upload[$n] = substr($texto,$texto_pos_ini,$tam_upl);
			$texto = substr($texto,$texto_final);

	if (stristr($upload[$n],".pdf")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"1\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".htm")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"2\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".doc")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"3\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".ppt")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"4\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".zip")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"5\"",$upload[$n]);
	}
	else {
		$upload[$n] = eregi_replace("<upl","<upl id=\"6\"",$upload[$n]);
	}
		$conteudo_final = $conteudo_final.$texto_aux[$n].$upload[$n];
		$n++;
		}//else
    }//while

        $conteudo_final = trim($conteudo_final.$texto);
        return $conteudo_final;

}

/*
 * NOME:		link_interno
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function link_interno($id,$conteudo,$dbh) {

//algoritmo de linkagem das paginas child
//se id=$ident.x & titulo=tupla[titulo] entao linkar
//c.c. [create]
                
//algoritmo de manipulacao dos links dentro da textarea
                        
$n="0";
$continua=true;
$texto = " ".$conteudo;
                        
  while ($continua == true)
        {                        
        $texto_pos_ini = strpos($texto,"<lnk>");
        if (!$texto_pos_ini)
                {       
                $continua=false ;
                } else
                        {
                        $texto_pos_fin = strpos($texto,"</lnk>") ;
                        $texto_ini = $texto_pos_ini + 5 ;
                        $texto_tam = $texto_pos_fin - $texto_ini;
                        $partes_texto[$n] = substr($texto,$texto_ini,$texto_tam);//proprio link
                        $ini_resto_texto = $texto_pos_fin + 6;
                        $texto = substr($texto,$ini_resto_texto);

//algoritmo de comparacao com o BD
$find=false;

                //encontra id_swiki
                $get_swiki = explode(".",$id);
                $swiki = $get_swiki[0];

//encontra paginas dentro de uma mesma swiki
$query_comp =  "select indexador,ident from paginas  where ((ident like '$swiki.%')  or (ident='$swiki'))";
$result_comp = mysql_query($query_comp,$dbh);
        
while (($tupla = mysql_fetch_array($result_comp))  && ($find==false))
        {
	//compara link do textarea com uma pagina do banco de dados
	if (!strcmp(trim($partes_texto[$n]),trim($tupla[indexador])))
	{
	//procura por identificador da pagina encontrada
        $query_find =  "select ident,data_ultversao from paginas where ((indexador='$tupla[indexador]') and ((ident like '$swiki.%')  or (ident='$swiki')))";
	$result_find = mysql_query($query_find,$dbh);
	$tupla_find = mysql_fetch_array($result_find); 	 	
	$pagina = $tupla_find[ident];
	$find = true;

        //acerta o formato da data
        $datetime = explode(" ",$tupla_find[data_ultversao]);
        $date = explode("-",$datetime[0]);
        $data_formato_correto = $date[2]."-".$date[1]."-".$date[0]." ".$datetime[1];
	}//if
}//while

//encontra a posicao correta na variavel, mesmo depois de inserir a linkagem

$texto_pos_inicio = strpos($conteudo,"<lnk>$partes_texto[$n]</lnk>");
$texto_inicio = $texto_pos_inicio + 5 ;
$texto_pos_final = $texto_inicio + $texto_tam;

if ($find==true)
        {
        $cont1 = substr_replace($conteudo,"</a>",$texto_pos_final,0);
        $cont2 = substr_replace($cont1,"<a href=\"mostra.php?ident=$pagina\" onmouseover=\"window.status='&Uacute;ltima Vers&atilde;o: $data_formato_correto'; return true\">",$texto_inicio,0);
        $texto_final = $cont2;
                                
	}//if
	else
        {
        $num =1;
	//procura por novo indice
        $query_search="select count(*) as CONTADOR from paginas where ((ident like '$id.%') and (ident not like '$id.%.%')) ORDER by ident asc";
        $result_search = mysql_query("$query_search",$dbh);
	$tupla_cont=mysql_fetch_array($result_search);
	$nro_pages=$tupla_cont[CONTADOR];

        $num = $nro_pages + 1;
	//indice de novas paginas
        $partes_texto[$n] = rawurlencode($partes_texto[$n]);
        $cont1 = substr_replace($conteudo,"[create]</a>",$texto_pos_final,0);
        $cont2 =substr_replace($cont1,"<a href=\"create.php?ident=$id.$num&amp;index=$partes_texto[$n]\" onmouseover=\"window.status='CoTeia'; return true\">",$texto_inicio+$texto_tam,0);
        $texto_final = $cont2;
                                
        }//else
 
                $n++; //incrementa a variavel de links
		$conteudo = $texto_final;
        
                }//else
        
    }//while

	return trim($texto_final);

}//function link_interno


/*
* API_note.php
*
* Sintaxe: $variavel = note($conteudo);
*
*/
function make_seed() {
        $return = (double)microtime()*1000000;
        return $return;
}

function note($conteudo) {
        
$continua=true;
$texto = " ".$conteudo;
$p1_ini = "0";        
$texto = eregi_replace("<note />","<note/>",$texto);

// cria a semente
srand(make_seed());

while ($continua == true)
        {
        $texto_pos_ini = strpos($texto,"<note/>");
        if (!$texto_pos_ini)
                {       
                $continua=false ;
                } else
                {
			$antes_note = substr($texto,$p1_ini,$texto_pos_ini);
                        $texto_fim = $texto_pos_ini + 5;
			$str = substr($texto,$texto_pos_ini,5);
	                $var = rand(1,1000);
			$str .= " id=\"@$var\"";
			$depois_note = substr($texto,$texto_fim); 
			$texto = $antes_note.$str.$depois_note;

                }
}
                        
        return trim($texto);
                        
}//function note

/*
 * NOME:		annotationLogin
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function annotationLogin($id)  {

    global $dbname;

    $dbh = db_connect();

    # seleciona base de dados
    mysql_select_db($dbname,$dbh);

    $comando_sql = "SELECT annotation_login FROM swiki WHERE id=$id";
    $resultado = mysql_query($comando_sql,$dbh);
   
    while ($tupla = mysql_fetch_array($resultado)) {
      $login = $tupla[annotation_login];
    }   
    
    if ($login == 'S')
      return TRUE;
    else
      return FALSE;
}

/*
 * NOME:		group_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */

 function group_exists($name)
  {
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

    $comando_sql = "SELECT * FROM t_group WHERE name='$name'";
    $resultado = mysql_query($comando_sql);

    if (mysql_num_rows($resultado))
      return 1;

    return 0;

} // fim function group_exists

/*
 * NOME:		authenticate_user
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function authenticate_user($login, $password)
{
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

  // Verifica se o login e a senha existem.
  $comando_sql = "SELECT id FROM t_user WHERE login='$login' AND password='$password'";
  $resultado = mysql_query($comando_sql);

  if (!mysql_num_rows($resultado)){
    if (!login_exists($login)) // login inexistente
      return -1;
    else // senha inválida
      return 0;
  }
  return mysql_result($resultado, 0, 'id');;
} // fim function authenticate_user

/*
 * NOME:		login_exists
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
 function login_exists($login)
 {
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

    $comando_sql = "SELECT * FROM t_user WHERE login='$login'";
    $resultado = mysql_query($comando_sql);

    if (mysql_num_rows($resultado))
      return 1;

    return 0;

 } // fim function login_exists

/*
 * NOME:		get_user_id
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
 function get_user_id($login)
 {
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

    $comando_sql = "SELECT id FROM t_user WHERE login='$login'";
    $resultado = mysql_query($comando_sql);

    return mysql_result($resultado, 0, 'id');

 } // fim function get_user_id

/*
 * NOME:		get_group_id
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
 function get_group_id($name)
  {
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

    $comando_sql = "SELECT id FROM t_group WHERE name='$name'";
    $resultado = mysql_query($comando_sql);

    return mysql_result($resultado, 0, 'id');

 } // fim function get_group_id

/*
 * NOME:		user_exists_in_group
 * DESCRICAO:		--
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:		
 */
function user_exists_in_group($id_user, $id_group)
{
    global $user_dbname;
    $user_dbh = user_db_pconnect();
    $select_db = mysql_select_db($user_dbname, $user_dbh);

    $comando_sql = "SELECT * FROM t_user_group WHERE id_user=$id_user AND id_group=$id_group";
    $resultado = mysql_query($comando_sql);

    if (mysql_num_rows($resultado))
      return TRUE;

    return FALSE;

} // fim function user_exists_in_group

?>
