<?php
   include "function.inc";
   $oldumask = umask(0);
   mkdir($PATH_UPLOAD."/".$id, 0777);
   umask($oldumask);
?>
