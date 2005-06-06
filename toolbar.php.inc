<div class="toolbar">


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="show.php?wikipage_id=<?php echo $wikipage_id; ?>"><img src="<?php echo $IMAGES_DIR; ?>/view.png" alt="View this Page" title="View" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/viewbw.png" alt="View this Page (disabled)" title="View (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="edit.php?wikipage_id=<?php echo $wikipage_id; ?>"><img src="<?php echo $IMAGES_DIR; ?>/edit.png" alt="Edit this Page" title="Edit" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/editbw.png" alt="Edit this Page (disabled)" title="Edit (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="edit.php?wikipage_id=<?php echo $wikipage_id; ?>&add=true"><img src="<?php echo $IMAGES_DIR; ?>/add.png" alt="Edit this Page (add mode)" title="Edit (add mode)" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/addbw.png" alt="Edit this Page (add mode) (disabled)" title="Edit (add mode) (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="history.php?wikipage_id=<?php echo $wikipage_id?>"><img src="<?php echo $IMAGES_DIR; ?>/history.png" alt="History of this Page" title="History" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/historybw.png" alt="History of this Page (disabled)" title="History (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="show.php?wikipage_id=<?php echo $swiki_id?>"><img src="<?php echo $IMAGES_DIR; ?>/indice.png" alt="Top of the Swiki" title="Top page" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/indicebw.png" alt="Top of the Swiki (disabled)" title="Top page (disabled)" />
<?php
}
?>


<?php
if ( isset( $id_swiki ) ) {
?>
<a href="JavaScript:AbreMapa(<?php echo $id_swiki?>)"><img src="<?php echo $IMAGES_DIR; ?>/map.png" alt="Mapa do Site" title="Map" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/mapbw.png" alt="Mapa do Site (disabled)" title="Map (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="changes.php?swiki_id=<?php echo $swiki_id?>"><img src="<?php echo $IMAGES_DIR; ?>/changes.png" alt="Recent Changes" title="Recent changes" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/changesbw.png" alt="Recent Changes (disabled)" title="Recent changes (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="repository.php?wikipage_id=<?php echo $wikipage_id?>"><img src="<?php echo $IMAGES_DIR; ?>/upload.png" alt="File Attachments" title="Attachments" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/uploadbw.png" alt="File Attachments (disabled)" title="Attachments (disabled)" />
<?php
}
?>


<?php
if ( isset( $wikipage_id ) ) {
?>
<a href="search.php?wikipage_id=<?php echo $wikipage_id?>"><img src="<?php echo $IMAGES_DIR; ?>/search.png" alt="Search the Swiki" title="Search" /></a>
<?php
} else {
?>
<img src="<?php echo $IMAGES_DIR; ?>/searchbw.png" alt="Search the Swiki (disabled)" title="Search (disabled)" />
<?
}
?>


<a href="help.php"><img src="<?php echo $IMAGES_DIR; ?>/help.png" alt="Help Guide" title="Help" /></a>

<img src="<?php echo $IMAGES_DIR; ?>/chatbw.png" alt="Chat" title="Chat" />

<img src="<?php echo $IMAGES_DIR; ?>/notebw.png" alt="Annotation" title="Annotation" />

<a href="javascript:Imprime()"><img src="<?php echo $IMAGES_DIR; ?>/print.png" alt="Print this Page" title="Print" /></a>

</div>
