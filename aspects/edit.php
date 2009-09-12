<?aophp filename="cvs.aophp,user_authentication.aophp,swiki_authentication.aophp" debug="off"
/*
Edit wikipages.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copyright (C) 2004 Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

include_once( "coteia.inc.php" );
include_once( "error.inc.php" );
include_once( "swiki.inc.php" );
include_once( "presentation.inc.php");
include_once( "wikipage.inc.php" );

include_once( "plugins/cvs/cvs-api.inc.php" );
include_once( 'plugins/metadata/metadata_functions.php.inc' );

// TODO:
// clear only cache for index.tpl
// $smarty->clear_cache('index.tpl');
// clear all caches with 'sports|basketball' as the first two cache_id groups
//$smarty->clear_cache(null,'sports|basketball');

// clear all caches with "sports" as the first cache_id group. This would
// include "sports|basketball", or "sports|(anything)|(anything)|(anything)|..."
//$smarty->clear_cache(null,'sports');


/**
* Checking the parameters.
*/

/**
* If creating a new wikipage, the request must have two parameters: "swiki_id"
* and "index". No more, no less.
* If editing a wikipage, the request must have the parameter "wikipage_id". No
* more, no less.
*
* First, we see if an action was specified. If not, we try to guess.
*/
if ( isset( $_REQUEST[ "action" ] ) ) {
	$action = $_REQUEST[ "action" ];
}

if ( !isset( $action) && isset( $_REQUEST[ "swiki_id" ] ) && isset( $_REQUEST[ "index" ] ) ) {
	$action = "create";
}
if ( !isset( $action) && isset( $_REQUEST[ "wikipage_id" ] ) ) {
	$action = "edit";
}

if ( !isset( $action ) ) {
	show_error( _( "An action was not specified and it was not possible to guess the desirable action. Contact the system administrator." ) );
}

if ( isset( $action ) && !( $action == "create" || $action == "edit"  ) ) {
	show_error( _( "Unsupported action: " ) . $action );
}


/**
* Known the action, the required parameters for each action is verified.
*/
if ( $action == "create" ) {
	$swiki_id = $_REQUEST[ "swiki_id" ];
	$index = $_REQUEST[ "index" ];
	if ( get_magic_quotes_gpc() == 1 ) {
		$index = stripslashes( $index );
	}
}

if ( $action == "edit" ) {
	$wikipage_id = $_REQUEST[ "wikipage_id" ];
	$swiki_id = extract_swiki_id( $wikipage_id );
}



/****************************************************************************
* Show time.
*/

// We will need the database from now on.
db_connect();
	
/**
* After futher checking the paremeters for each action, the skeleton of a
* wikipage must be createn (wikipage_raw). If creating a new wikipage, it
* really is a skeleton. If editing a wikipage, the current data is loaded
* fro the database.
*/
if ( $action == "create" ) {
	$query = "select COUNT(*) as counter from paginas,gets where gets.id_sw='$swiki_id' and gets.id_pag=paginas.ident and binary indexador='" . mysql_escape_string( $index ) . "'";
	$result = mysql_query( $query );
	$tuple = mysql_fetch_row( $result );
	if ( $tuple[ 0 ] != 0 ) {
		show_error( _( "There is a wikipage with the requested index already." ) );
	}
	mysql_free_result( $result );

	$wikipage_raw = array();
	$wikipage_raw[ "ident" ] = 0;
	$wikipage_raw[ "indexador" ] = $_REQUEST[ "index" ];
	$wikipage_raw[ "autor" ] = "";
	$wikipage_raw[ "titulo" ] = $_REQUEST[ "index" ];
	$wikipage_raw[ "kwd1" ] = "";
	$wikipage_raw[ "kwd2" ] = "";
	$wikipage_raw[ "kwd3" ] = "";
	$wikipage_raw[ "pass" ] = NULL;
	$wikipage_raw[ "conteudo" ] = "";
}

// If we are editing a wikipage, load the requested wikipage's data from the database.
if ( $action == "edit" ) {
	// Check if there's a wikipage with the given "ident".
	$query = "select * from paginas where ident='" . $wikipage_id . "'";
	$result = mysql_query( $query );
	if ( mysql_num_rows( $result ) == 0 ) {
		show_error( _( "The requested wikipage couldn't be found. Please contact the swiki's administrator." ) );
	}
	$wikipage_raw = mysql_fetch_array( $result );
}

$metadata = metadata_initialize($wikipage_id);
$feedback = metadata_feedback_initialize($wikipage_id);

// Apply user templates
$metadata = getMetadataFromTemplate($wikipage_id, $metadata, $feedback);

// Only the title is suggested upon wikipage creation
$metadata['general']['title'] = $wikipage_raw[ "titulo" ];
$metadata['generalTitle'] = $wikipage_raw[ "titulo" ];



/**
* This is the second phase of every action, the data commitment.
*/
if ( isset( $_REQUEST[ "save" ] ) ) {

	$metadata_old = $metadata;
	$metadata = updateMetadataUsingHTTPRequest($metadata);
	$feedback = updateFeedbackUsingHTTPRequest($feedback);

	checkContentChange($metadata, $wikipage_raw);
	checkUserMetadataChange($metadata_old, $metadata, $feedback);

	getMetadataFromAutomaticExtracting($wikipage_id, $metadata, $feedback);
	getMetadataFromTemplateSystem($wikipage_id, $metadata);
	
	writeMetadataRecord($wikipage_id, $metadata);
	writeFeedbackRecord($wikipage_id, $feedback);

	$file = $XML_DIR . '/metadata' . $wikipage_id . ".xml";
	writeMetadataToFile($file, $metadata);
	$file = $XML_DIR . '/metadata' . $wikipage_id . ".rdf";
	writeMetadataToRDFFile($file, $metadata);	

	// Check password (if there is one to check against).
	$password = $wikipage_raw[ "pass" ];
	if ( $password != NULL ) {
		if ( strcasecmp( $password, md5( $_REQUEST[ "password" ] ) ) != 0 ) {
			show_error( _( "Incorrect password. Please, try again." ) );
		}
	}

	/**
	* When creating a new wikipage, a new (and unique) identificator (wikipage_id) must be
	* generated. If it's the swiki's root (in other words, the swiki's first wikipage),
	* the "wikipage_id" will be the same as the "swiki_id". Otherwise, it will be the
	* swiki's wikipage's counter prefixed by "$swiki_id." (this will start with "1").
	*/
	if ( $action == "create" ) {
		$query = "select count(*) as counter from paginas where ident like '$swiki_id.%' or ident='$swiki_id'";
		$result = mysql_query( $query );
		$tuple = mysql_fetch_array( $result );
		if ( $tuple[ "counter" ] == 0 ) {
			$wikipage_id = $swiki_id;
		} else {
			$wikipage_id = $swiki_id . "." . $tuple[ "counter" ];
		}
	}

	// Handle the case the wikipage's content is sent via an upload file.
	if ( isset( $_FILES[ "filename" ] ) ) {
		if ( is_uploaded_file( $_FILES['filename']['tmp_name'] ) ) {
			$_REQUEST[ "content" ] =  file_get_contents( $_FILES["filename"]["tmp_name"] );
		}
	}

	// Check were to insert the new data (above or below).
	if ( isset( $_REQUEST[ "position" ] ) ) {
	  if ( $_REQUEST[ "position" ] == "bottom" ) {
  	  $_REQUEST[ "content" ] = $wikipage_raw[ "conteudo" ] . $_REQUEST[ "content" ];
	  } else {
  	  $_REQUEST[ "content" ] = $_REQUEST[ "content" ] . $wikipage_raw[ "conteudo" ];
	  }
	}

	$wikipage_raw[ "titulo" ] = $_REQUEST[ "title" ];
	$wikipage_raw[ "autor" ] = $_REQUEST[ "author" ];
	$wikipage_raw[ "kwd1" ] = $_REQUEST[ "keyword1" ];
	$wikipage_raw[ "kwd2" ] = $_REQUEST[ "keyword2" ];
	$wikipage_raw[ "kwd3" ] = $_REQUEST[ "keyword3" ];
	$wikipage_raw[ "conteudo" ] = $_REQUEST[ "content" ];

	/**
	* Check the wikipage for syntax errors before saving the data.
	*/
	$validation_result = validate_wikipage( $wikipage_id, $wikipage_raw );
	if ( $validation_result !== true ) {
		show_error( $validation_result );
	}

	// Prepare data for database insertion.
	$wikipage_db = prepare_for_db( $wikipage_raw );

	// Set wikipage's edition protection.
	if ( !isset( $_REQUEST[ "lock" ] ) ) {
		$wikipage_db[ "password" ] = "NULL";
	} else {
		$wikipage_db[ "password" ] = "" . $_REQUEST[ "password" ];
		if ( get_magic_quotes_gpc() == 1 ) {
			$wikipage_db[ "password" ] = stripslashes( $wikipage_db[ "password" ] );
		}
		$wikipage_db[ "password" ] = "'" . md5( $wikipage_db[ "password" ] ) . "'";
	}

	$d = getdate();
	$date=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

	if ( $action == "create" ) {
		$ip = getenv( "REMOTE_ADDR" );
		$query = "insert into paginas (ident,indexador,titulo,conteudo,ip," .
			"data_criacao,data_ultversao,pass,kwd1,kwd2,kwd3,autor) values (" .
			"'$wikipage_id'" .
			",'" . mysql_escape_string( $index ) . "'" .
			",'" .$wikipage_db[ "title" ] . "'" .
			",'" .$wikipage_db[ "content" ] . "'" .
			",'" .$ip . "'" .
			",'" .$date . "'" .
			",'" .$date . "'" .
			"," . $wikipage_db[ "password" ] . "" .
			",'" .$wikipage_db[ "keyword1" ] . "'" .
			",'" .$wikipage_db[ "keyword2" ] . "'" .
			",'" .$wikipage_db[ "keyword3" ] . "'" .
			",'" .$wikipage_db[ "author" ] . "')";

		$result = mysql_query( $query );
		if ( $result == false && mysql_affected_rows() != 1 ) {
			show_error( _( "It wasn't possible to create the wikipage. Please, try again. If the error persist, contact the system administrator." ) );
		}

		$query = "insert into gets (id_pag,id_sw,data) values (" .
			"'$wikipage_id'" .
			",'$swiki_id'" .
			",'$date')";
		$result = mysql_query( $query );
		if ( $result == false && mysql_affected_rows() != 1 ) {
			show_error( _( "It wasn't possible to associate the wikipage to the swiki. Please, try again. If the error persist, contact the system administrator." ) );
		}
	}

	if ( $action == "edit" ) {
		$query = "update paginas set " .
			"conteudo='" . $wikipage_db[ "content" ]     . "'," .
			"titulo='"   . $wikipage_db[ "title" ]       . "'," .
			"kwd1='"     . $wikipage_db[ "keyword1" ] . "'," .
			"kwd2='"     . $wikipage_db[ "keyword2" ] . "'," .
			"kwd3='"     . $wikipage_db[ "keyword3" ] . "'," .
			"autor='"    . $wikipage_db[ "author" ]      . "'," .
			"data_ultversao='" . $date                   . "'," .
			"pass="      . $wikipage_db[ "password" ]    . "  " .
			"where ident='"    . $wikipage_id            . "'";

		$result = mysql_query( $query );
		$result = true;

		if ( $result == false && mysql_affected_rows() != 1 ) {
			show_error( _( "It wasn't possible to update the wikipage. Please, try again. If the error persist, contact the system administrator." ) );
		}
	}

	$format = $DEFAULT_OUTPUT_FORMAT;
	$result = update_wikipage( $wikipage_id, $format );
	if ( $result !== true ) {
    	show_error( _( "An error has been found in this wikipage. Please, contact the system administrator." ) );
	}
	

	session_write_close();
	header("Location: show.php?wikipage_id=$wikipage_id");
	exit();
} else {

echo get_header( _( "Edit wikipage" ) );
?>
	<script type="text/javascript" filename="collapse.js" />
</head>

<body>

<?php
include( "toolbar.inc.php" );
?>


<form method="post" name="edit" action="edit.php" onSubmit="return validar(this);" enctype="multipart/form-data">
 
<div class="metadata">
<table>
<tr>
	<td>
	<?php echo _( "Title" ); ?>
	<br /><input type="text" name="title" value="<?php echo $wikipage_raw[ "titulo" ];?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	<?php echo _( "Author" ); ?>
	<br /><input type="text" name="author" value="<?php echo $wikipage_raw[ "autor" ]; ?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	<?php echo _( "Keywords" ); ?>
	<br />
	<input type="text" name="keyword1" size="15" value="<?php echo $wikipage_raw[ "kwd1" ]; ?>" />
	<input type="text" name="keyword2" size="15" value="<?php echo $wikipage_raw[ "kwd2" ]; ?>" />
	<input type="text" name="keyword3" size="15" value="<?php echo $wikipage_raw[ "kwd3" ]; ?>" />
	</td>
</tr>
</table>
</div>

<div class="lock">
	<?php echo _( "Lock" ); ?>
	<br /><input type="checkbox" name="lock" <?php if ( $wikipage_raw[ "pass" ] != NULL ) echo " checked"; ?> />

	<br /><?php echo _( "Password" ); ?>
	<br /><input type="password" size="10" name="password" onChange="window.document.edit.lock.checked=true;return true;" />

<?php
	if ( $wikipage_raw[ "pass" ] == NULL ) {
?>
	<br /><?php echo _( "Re-enter password" ); ?>
	<br /><input type="password" size="10" name="repassword" onChange="window.document.edit.lock.checked=true;return true;" />
<?php
	}
?>
</div>

<?php
	if ( isset( $_REQUEST[ "add" ] ) ) {
?>
<div class="optional">
  <strong><?php echo _( "Where to add the text" ); ?></strong>
  <br /><input type="radio" name="position" value="top" checked />Acima
  <br /><input type="radio" name="position" value="bottom" />Abaixo
</div>

<br />

<div class="content" >
	<input type="reset" value="<?php echo _( "Reset" ); ?>" />
	<input type="submit" name="save" value="<?php echo _( "Save" ); ?>" />
	<br />
	<textarea name="content" wrap=virtual rows="7" cols="100" style="width: 100%"></textarea>

	<br />
	<iframe src="show.php?wikipage_id=<?php echo $wikipage_id; ?>" width="100%" height="30%" scrolling="auto" frameborder="1">
	<?php echo _( "Your browser does not support frames or is currently configured not to display frames. You can preview the current document by <a href=\"show.php?wikipage_id=$wikipage_id\">following this link: Preview wikipage</a>." ); ?>
  </iframe>
</div>

<?php
	} else {
?>

<br />
<div class="content" >
	<input type="reset" value="<?php echo _( "Reset" ); ?>" onClick="return confirm('<?php echo _( "Are you sure? This will restore the original text\n(in another words, you will lose every change made to the text)." ); ?>');" />
	<input type="submit" name="save" value="<?php echo _( "Save" ); ?>" />
	<input type="file" size="40" name="filename" />
	<br />
	<textarea name="content" wrap=virtual rows="15" cols="100" style="width: 100%"><?php echo $wikipage_raw[ "conteudo" ]; ?></textarea>
</div>
<?php
	}
?>

<fieldset class="collapsible">
<legend><?php echo _("Metadata"); ?></legend>
<div class="form-item" id="metadata">
<!-- Título (General.Title) -->
<p></p>
<a href="metadata/help/help_titulo.html">
	<strong><?php echo _( "Title" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('title'); ?>" id="buttonTitle" onclick="replaceMode('buttonTitle', 'modeTitle');" />
<input type="hidden" name="modeTitle" id="modeTitle" value="<?php echo getFeedbackValue('title'); ?>" />
<input type="text" size="55" name="title" value="<?php echo $metadata['generalTitle']; ?>">


<!-- Descrição (General.Description) -->
<p></p>
<a href="metadata/help/help_descricao.html">
	<strong><?php echo _( "Description" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('description'); ?>" id="buttonDescription" onclick="replaceMode('buttonDescription', 'modeDescription');" />
<input type="hidden" name="modeDescription" id="modeDescription" value="<?php echo getFeedbackValue('description'); ?>" />
<textarea name="description" rows="3" cols="50"><?php echo $metadata['generalDescription'] ?></textarea>


<!-- Idioma (General.Language)-->
<p></p>
<a href="metadata/help/help_idioma.html">
	<strong><?php echo _( "Language" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('language_page'); ?>" id="buttonLanguage" onclick="replaceMode('buttonLanguage', 'modeLanguage');" />
<input type="hidden" name="modeLanguage" id="modeLanguage" value="<?php echo getFeedbackValue('language_page'); ?>" />
<select multiple size="3" name="language">
<?php
	$available_languages = csv2array("plugins/metadata/metadata_language.csv");
	foreach ($available_languages as $language) {
		if (in_array($language, $metadata['generalLanguage'])) {
			print "<option value=\"$language\" selected=\"selected\">$language</option>";
		} else {
			print "<option value=\"$language\">$language</option>";
		}
	}
?>
</select>


<!-- Palavras-chave (General.Keyword) -->
<p></p>
<a href="metadata/help/help_palavras_chave.html">
	<strong><?php echo _( "Keywords" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('keywords'); ?>" id="buttonKeyword" onclick="replaceMode('buttonKeyword', 'modeKeyword');" />
<input type="hidden" name="modeKeyword" id="modeKeyword" value="<?php echo getFeedbackValue('keywords'); ?>" />
<?php
	if (!isset($metadata['generalKeyword'])) $metadata['generalKeyword'] = array();
	$i = 0;
	foreach ($metadata['generalKeyword'] as $keyword) {
		print "<input size=\"15\" name=\"keyword[$i]\" value=\"$keyword\">\n";
		$i++;
        }
	print "<input size=\"15\" name=\"keyword[$i]\" value=\"$keyword\">\n";
?>


<!-- Tópico (Classification) -->
<p></p>
<a href="metadata/help/help_topico.html">
	<strong><?php echo _( "Topic"); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('topic'); ?>" id="buttonTopic" onclick="replaceMode('buttonTopic', 'modeTopic');" />
<input type="hidden" name="modeTopic" id="modeTopic" value="<?php echo getFeedbackValue('topic'); ?>" />
<select multiple size="3" name="topic">
<?php
	$available_topics = csv2array("plugins/metadata/metadata_topic.csv");
	foreach ($available_topics as $topic) {
		if (in_array($topic, $metadata['localTopic'])) {
			print "<option value=\"$topic\" selected=\"selected\">$topic</option>";
		} else {
			print "<option value=\"$topic\">$topic</option>";
		}
	}
?>
</select>


<!-- Disciplina (Classification) -->
<p></p>
<a href="metadata/help/help_disciplina.html">
	<strong><?php echo _( "Course" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('course'); ?>" id="buttonCourse" onclick="replaceMode('buttonCourse', 'modeCourse');" />
<input type="hidden" name="modeCourse" id="modeCourse" value="<?php echo getFeedbackValue('course'); ?>" />
<select multiple size="3" name="course">
<?php
	$available_courses = csv2array("plugins/metadata/metadata_course.csv");
	foreach ($available_courses as $course) {
		if (in_array($course, $metadata['localCourse'])) {
			print "<option value=\"$course\" selected=\"selected\">$course</option>";
		} else {
			print "<option value=\"$course\">$course</option>";
		}
	}
?>
</select>


<!-- Material didático (Classification) -->
<p></p>
<a href="metadata/help/help_material.html">
	<strong><?php echo _( "Didatic material" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('material'); ?>" id="buttonMaterial" onclick="replaceMode('buttonMaterial', 'modeMaterial');" />
<input type="hidden" name="modeMaterial" id="modeMaterial" value="<?php echo getFeedbackValue('material'); ?>" />
<select multiple size="3" name="material">
<?php
	$available_materials = csv2array("plugins/metadata/metadata_material.csv");
	foreach ($available_materials as $material) {
		if (in_array($material, $metadata['localMaterial'])) {
			print "<option value=\"$material\" selected=\"selected\">$material</option>";
		} else {
			print "<option value=\"$material\">$material</option>";
		}
	}
?>
</select>


<!-- Atividade de ensino-aprendizagem (Classification) -->
<p></p>
<a href="metadata/help/help_atividade.html">
	<strong><?php echo _( "Teaching and learning activities" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('activity'); ?>" id="buttonActivity" onclick="replaceMode('buttonActivity', 'modeActivity');" />
<input type="hidden" name="modeActivity" id="modeActivity" value="<?php echo getFeedbackValue('activity'); ?>" />
<select multiple size="3" name="activity">
<?php
	$available_activities = csv2array("plugins/metadata/metadata_activity.csv");
	foreach ($available_activities as $activity) {
		if (in_array($activity, $metadata['localLearningActivity'])) {
			print "<option value=\"$activity\" selected=\"selected\">$activity</option>";
		} else {
			print "<option value=\"$activity\">$activity</option>";
		}
	}
?>
</select>


<!-- Contexto (Classification) -->
<p></p>
<a href="metadata/help/help_contexto.html">
	<strong><?php echo _( "Educational context" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('brazilian_context'); ?>" onclick="replaceMode('buttonBrazilianContext', 'modeBrazilianContext');" />
<input type="hidden" name="modeBrazilianContext" id="modeBrazilianContext" value="<?php echo getFeedbackValue('brazilian_context'); ?>" />
<select multiple size="3" name="brazilian_context">
<?php
	$available_brazilian_contexts = csv2array("plugins/metadata/metadata_brazilian_context.csv");
	foreach ($available_brazilian_contexts as $context) {
		if (in_array($context, $metadata['localContent'])) {
			print "<option value=\"$context\" selected=\"selected\">$context</option>";
		} else {
			print "<option value=\"$context\">$context</option>";
		}
	}
?>
</select>

  
<!-- Modalidade (Classification)-->
<p></p>
<a href="metadata/help/help_modality.html">
	<strong><?php echo _( "Modality" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('brazilian_modality'); ?>" id="buttonModality" onclick="replaceMode('buttonModality', 'modeModality');" />
<input type="hidden" name="modeModality" id="modeModality" value="<?php echo getFeedbackValue('brazilian_modality'); ?>" />
<select multiple size="3" name="modality">
<?php
	$available_modalities = csv2array("plugins/metadata/metadata_modality.csv");
	foreach ($available_modalities as $modality) {
		if (in_array($modality, $metadata['localModality'])) {
			print "<option value=\"$modality\" selected=\"selected\">$modality</option>";
		} else {
			print "<option value=\"$modality\">$modality</option>";
		}
	}
?>
</select>


<!-- Dificuldade (Educational.Difficulty) -->
<p></p>
<a href="metadata/help/help_dificuldade.html">
	<strong><?php echo _( "Difficult (in relation to the main final user and the chosen context)" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('difficulty'); ?>" id="buttonDifficulty" onclick="replaceMode('buttonDifficulty', 'modeDifficulty');" />
<input type="hidden" name="modeDifficulty" id="modeDifficulty" value="<?php echo getFeedbackValue('difficulty'); ?>" />

<input name="difficulty" value="easy" type="radio" <?php if (strcasecmp($metadata['educationalDifficulty'], "easy") == 0) echo "checked=check"; ?>><?php echo _( "Easy" ); ?></input>
<input name="difficulty" value="medium" type="radio" <?php if (strcasecmp($metadata['educationalDifficulty'], "medium") == 0) echo "checked=check"; ?>><?php echo _( "Medium" ); ?></input>
<input name="difficulty" value="difficult" type="radio" <?php if (strcasecmp($metadata['educationalDifficulty'], "difficult") == 0) echo "checked=check"; ?>><?php echo _( "Difficult" ); ?></input>
<input name="difficulty" value="very difficult" type="radio" <?php if (strcasecmp($metadata['educationalDifficulty'], "very difficult") == 0) echo "checked=check"; ?>><?php echo _( "Very difficult" ); ?></input>


<!-- Comentários (Annotation.Description)-->
<p></p>
<a href="metadata/help/help_comentarios.html">
	<strong><?php echo _( "Comments (about how the page is used within the pedagogic context)" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('annotation'); ?>" id="buttonAnnotation" onclick="replaceMode('buttonAnnotation', 'modeAnnotation');" />
<input type="hidden" name="modeAnnotation" id="modeAnnotation" value="<?php echo getFeedbackValue('annotation'); ?>" />
<?php
	$i = 0;
	if ( ! isset($metadata['annotationDescription']) ) $metadata['annotationDescription'] = array();
	foreach ($metadata['annotationDescription'] as $annotation) {
		echo "<textarea name=\"annotation[$i]\" rows=\"3\" cols=\"50\">";
		echo $annotation;
		echo "</textarea>";
		$i++;
	}
	echo "<textarea name=\"annotation[$i]\" rows=\"3\" cols=\"50\">";
	echo "</textarea>";
?>
    

<!-- Direitos autorais e condicões de uso (Rights) -->
<p></p>
<a href="metadata/help/help_direitos.html">
	<strong><?php echo _( "Cost" ); ?></strong>
</a>
<br />
<input type="button" value="<?php echo getPrettyPrintFeedbackValue('rights'); ?>" id="buttonRights" onclick="replaceMode('buttonRights', 'modeRights');" />
<input type="hidden" name="modeRights" id="modeRights" value="<?php echo getFeedbackValue('rights'); ?>" />
<input name="cost" value="yes" type="radio" <?php if (strcasecmp($metadata['rightsCost'], "yes") == 0) print "checked=\"checked\""; ?>><?php echo _( "Yes" ); ?></input>
<input name="cost" value="no" type="radio" <?php if (strcasecmp($metadata['rightsCost'], "no") == 0) print "checked=\"checked\""; ?>><?php echo _( "No" ); ?></input>

<p></p>
<a href="metadata/help/help_direitos.html">
	<strong><?php echo _( "Is copyrighted?" ); ?></strong>
</a>
<br />
<input name="copyright" value="yes" type="radio" <?php if (strcasecmp($metadata['rightsCopyrightAndOtherRestrictions'], "yes") == 0) print "checked=\"checked\""; ?>>Sim</input>
<input name="copyright" value="no" type="radio" <?php if (strcasecmp($metadata['rightsCopyrightAndOtherRestrictions'], "no") == 0) print "checked=\"checked\""; ?>>Não</input>


<p></p>
<a href="metadata/help/help_direitos.html">
	<strong><?php echo _( "License" ); ?></strong>
</a>
<br />
<textarea name="comments" rows="3" cols="50">
<?php
	echo $metadata['rightsDescription'];
?>
</textarea>
</fieldset>   
</div>


	<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_raw[ "ident" ]; ?>" />
<?php
	if ( isset( $index ) ) {
?>
	<input type="hidden" name="index" value="<?php echo $wikipage_raw[ "indexador" ]; ?>" />
	<input type="hidden" name="swiki_id" value="<?php echo $swiki_id; ?>" />
<?php
	}
?>

</form>

<?php
}
?>

</body>

</html>
