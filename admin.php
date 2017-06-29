<?php
  include 'lib.php';

  if(isset($_POST['btn'])) {
  	if (isset($_FILES)) {

  	  if (!file_exists($dest)) { mkdir($dest); }
      foreach($_FILES as $key => $val) {
        // проверка на имя:
        foreach($val['name'] as $sub_key => $filename) {
          preg_match($pattern, $filename, $matches); 
          if (count($matches) < 1) {
          	echo "Файл $filename неверного формата<br/>";
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
  <link rel="stylesheet" href="localhost/gentest.css">
	<meta charset="utf-8">
</head>
<body>
  
  <form action="" method="POST" enctype="multipart/form-data">
    <fieldset>
    <legend>Загрузчик тестов</legend>
    <label>Файлы:<br/><input type="file" name="tests[]" multiple required></label><br/><br/>
    <input type="submit" value="Загрузить тесты" name="btn">
    <div class="farea hidden">
      <p>Список выбранных файлов:</p>
      <ul class="list">
      </ul>
      <textarea id="fileContent" class="hidden" rows="10" cols="80"></textarea>
    </div><br/>
    </fieldset>
  </form>
  <a href="list.php" target="_blank">Список загруженных тестов</a>
  <script type="text/javascript">
    'use strict';
    let forma=new FormData();
    document.querySelector('input[type=file]').addEventListener('change',chooseFile); // обработчик на поле - выбор файлов
    document.querySelector('.farea').addEventListener('click',readFile); // обработчик на див - чтение файла по ссылке

    // Обработчик события выбора файлов в форме:
    function chooseFile(event) {
      const fragment = document.createDocumentFragment();
      const divarea = document.querySelector('.farea');
      const ularea = document.querySelector('.list');
      const files = Array.from(event.target.files);
      Array.from(ularea.children).forEach(lishka => {ularea.removeChild(lishka);}); // удаляем прежние лишки
      divarea.classList.remove('hidden');
      files.forEach(file => {
        const a = document.createElement('a');
        const li = document.createElement('li');
        a.setAttribute("href","#");
        forma.append(file.name, file);
        a.innerHTML=file.name;
        li.appendChild(a);
        fragment.appendChild(li);
      });
      ularea.appendChild(fragment);
    }

    // обработчик события клика по ссылке с именем файла:
    function readFile(event) {  
      if (event.target.tagName != "A") { return; }
      event.preventDefault();
      const reader = new FileReader();
      const fileContent=document.getElementById("fileContent");
      let myfile=forma.get(event.target.textContent);
      reader.addEventListener('load', event=> {
        fileContent.value = event.target.result;
        console.log(fileContent.value);
      });
      reader.readAsText(myfile);
    }
  </script>
</body>
</html>
