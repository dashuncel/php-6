<?php
include 'lib.php';
readDest($dest);

if (isset($_GET['test'])) {
  $result=array_search($_GET['test'].".json", $filelist);
  if ($result >= 0) {
    $data=file_get_contents($filelist[$result]);
    $jsonData=json_decode($data, JSON_UNESCAPED_UNICODE); 
    if (json_last_error() != 0) {
      echo "Ошибка чтения json файла ".json_last_error(); 
    }
  } else {
    echo "Что-то пошло не так. Не могу найти файл с тестом ".$_GET["test"].".json"; 
  }
}

print_r($jsonData);
/*
foreach ($jsonData as $key => $val) {
  print_r($val);
}
*/
?> 