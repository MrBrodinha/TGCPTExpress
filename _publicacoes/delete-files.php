<?php
//Recieves an argument with GET which is the name of the file to be deleted in imagem/
//Deletes the file and closes self window
$filename = $_GET["filename"];
unlink("imagem/" . $filename);
echo "<script>window.close();</script>";
?>