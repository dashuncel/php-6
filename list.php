<?php

include 'lib.php';

readDest($dest);

// генерирует liшки для html
function setList() {
  global $filelist;
  foreach ($filelist as $test) {
  	$test=explode(".",$test)[0];
  	switch ($test) {
  		case 'astrology':
  			$test_name="Тест по астрологии";
  			break;
  		case 'astronomy':
  			$test_name="Тест по астрономии";
  			break;
  		case 'philosophy':
  			$test_name="Тест по философии";
  			break;
  		default:
  			$test_name="Неизвестный тест";
  			break;
  	}
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
  <h1>Тесты</h1>
  <ul class="filelist">
  <?php 
    setList(); 
  ?>
  </ul>
  <a href="admin.php" target="_blank">Загрузить тесты</a>
</body>
</html>
