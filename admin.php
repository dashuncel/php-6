<?php
  if(isset($_POST['btn'])) {
  	if (isset($_FILES)) {
  	  $dest="tests";
  	  $pattern="/^[0-9a-z]*.json$/";
  	  if (!file_exists($dest)) { mkdir($dest); }
      foreach($_FILES as $key => $val) {
        // проверка на имя:
        foreach($val['name'] as $sub_key => $filename) {
          preg_match($pattern, $filename, $matches); 
          if (count($matches) < 1) {
          	echo "Файл $filename неверного формата";
          	continue;
          }
          if (move_uploaded_file($val['tmp_name'][$sub_key], $dest.$filename)) {
             echo "Файл $filename успешно загружен в каталог $dest<br/>";
          } else {
             echo "Ошибка загрузки файла $filename в каталог $dest<br/>";
          }
        }
      }
  	}
  }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Генератор тестов</title>
	<meta charset="utf-8">
</head>
<body>

  <form action="" method="POST" enctype="multipart/form-data">
    <fieldset>
    <legend>Загрузчик тестов</legend>
    <label>Файлы:<br/><input type="file" name="tests[]" multiple required></label><br/><br/>
    <input type="submit" value="Загрузить тесты" name="btn">
    </fieldset>
  </form>
  <a href="test.php" target="_blank">Список загруженных тестов</a>
</body>
</html>
