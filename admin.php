<?php
  if(isset($_POST['btn'])) {
  	echo "$_POST";
  	if (isset($_FILES)) {
      foreach($_FILES as $key => $val) {

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
    <label>Файлы:<br/><input type="file" name="tests" multilple></label><br/><br/>
    <input type="submit" value="Загрузить тесты" name="btn">
    </fieldset>
  </form>
  <a href="test.php" target="_blank">Список загруженных тестов</a>
</body>
</html>
