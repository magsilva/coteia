<div class="toolbar">

<a href="mostra.php?ident=<?php echo $ident; ?>">
	<img src="<?php echo $URL_IMG?>/view.png" alt="View this Page" />
</a>

<a href="edit.php?ident=<?php echo $ident; ?>">
	<img src="<?php echo $URL_IMG?>/edit.png" alt="Edit this Page"/>
</a>

<a href="history.php?ident=<?php echo $ident?>">
	<img src="<?php echo $URL_IMG?>/history.png" alt="History of this Page" />
</a>

<a href="mostra.php?ident=<?php echo $id_swiki?>">
	<img src="<?php echo $URL_IMG?>/indice.png" alt="Top of the Swiki" />
</a>

<a href="JavaScript:AbreMapa(<?php echo $id_swiki?>)">
	<img src="<?php echo $URL_IMG?>/map.png" alt="Mapa do Site" />
</a>

<a href="changes.php?ident=<?php echo $ident?>">
	<img src="<?php echo $URL_IMG?>/changes.png" alt="Recent Changes" />
</a>

<a href="upload.php?ident=<?php echo $ident?>">
	<img src="<?php echo $URL_IMG?>/upload.png" alt="File Attachments" />
</a>

<a href="search.php?ident=<?php echo $ident?>">
	<img src="<?php echo $URL_IMG?>/search.png" alt="Search the Swiki" />
</a>

<a href="help.php">
	<img src="<?php echo $URL_IMG?>/help.png" alt="Help Guide" />
</a>

<a href="JavaScript:AbreChat(<?php echo $id_swiki?>)">
	<img src="<?php echo $URL_IMG?>/chat.png" alt="ChatServer" />
</a>

<a href="JavaScript:AbreAnotacao(<?php echo "$id_swiki,$ident,$id_ann"?>">
	<img src="<?php echo $URL_IMG?>/note.png" alt="GroupNote" />
</a>

<a href="JavaScript:Imprime()">
	<img src="<?php echo $URL_IMG?>/print.png" alt="Print this Page" />
</a>

</div>
