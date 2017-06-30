<?php

include 'lib.php';

readDest($dest); // чтение директории и заполнение глобальной переменной $filelist

// генерирует liшки для html со списком файлов
function setList() {
  global $filelist;
  foreach ($filelist as $test) {
  	$test=explode(".",$test)[0];
    $test_name=getTestName($test);
   	echo "<li><a href=\"test.php?test=$test\">$test_name</a></li>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Список загруженных тестов</title>
	<link rel="stylesheet" href="./gentest.css">
	<meta charset="utf-8">
</head>
<body>
  <?php getMainMenu(); ?>
  <ul class="filelist list">
  <?php 
    setList(); 
  ?>
  </ul>
</body>
</html>
