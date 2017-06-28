<?php 
 
$dest="tests".DIRECTORY_SEPARATOR; // директория сфайлами теста
$pattern="/^[0-9a-z]*.json$/"; // маска валидных файлов
$filelist=[]; // список файлов

function readDest($destination) {
  $cdir=scandir($destination);
  global $filelist;
  global $pattern;

  foreach ($cdir as $key => $value) { 
    if (! is_dir($value)) {
      preg_match($pattern, $value, $matches);
      if (count($matches) < 1) { continue; }
      $filelist[]=$value;
    } 
  }
}

?>
