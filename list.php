<?php

include 'lib.php';
$cdir=scandir($dest);
$filelist=[];

foreach ($cdir as $key => $value) { 
  if (! is_dir($value)) {
     preg_match($pattern, $value, $matches);
     if (count($matches) < 1) { continue; }
     $filelist[]=$value;
  } 
}

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
</body>
</html>
