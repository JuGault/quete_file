<?php

foreach ($_POST as $file) {
   if (file_exists($file)) {
       unlink($file);
   }
}
header('location: upload.php');
