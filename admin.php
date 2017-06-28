<?php
  if(isset($_POST['btn'])) {
  	echo "<pre>";
  	print_r($_FILES);
  	echo "</pre>";
  	if (isset($_FILES)) {
  	  $dest="./tests";
  	  if (!file_exists($dest)) { mkdir($dest); }
      foreach($_FILES as $key => $val) {
        if (move_uploaded_file($val,$dest)) {
           echo "Файл $val успешно загружен в каталог $dest";
        } else {
           echo "Ошибка загрузки файла $val в каталог $dest";
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
    <label>Файлы:<br/><input type="file" name="tests" multiple required></label><br/><br/>
    <input type="submit" value="Загрузить тесты" name="btn">
    </fieldset>
  </form>
  <a href="test.php" target="_blank">Список загруженных тестов</a>
</body>
</html>
