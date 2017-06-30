<?php
include 'lib.php';
readDest($dest);
$jsonData=[];

// обработка get-запроса
if (isset($_GET['test'])) {
  $nametest=getTestName($_GET['test']);
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

// обработка post-запроса
if (isset($_POST['btn_check'])) {

}

// заполнение формы в HTML
function fillForm() {
  global $jsonData;
  $answers=[];
  $r_answer='';

  foreach ($jsonData as $key => $question) {
    $name=$key;
    foreach ($question as $s_key => $qdata) {
      switch ($s_key) {
         case 'Вопрос':
           $question=$qdata;
         break;
         case 'Варианты':
           $answers=$qdata;
         break;
         case 'Ответ':
           $r_answer=$qdata;
         break;
      }    
    }
    echo "<label>$question</label>";
    echo "<ul class=\"answers\">";
    if (count(explode(",", $r_answer)) > 1) {
      foreach ($answers as $key => $ans) {
        echo "<li><input type=\"checkbox\" name=\"$name\" value=\"$key\"/>$ans</li>";
      }  
    } else {
      foreach ($answers as $key => $ans) {
        echo "<li><input type=\"radio\" name=\"$name\" value=\"$key\"/>$ans</li>";
      }
    }
    echo "</ul>";
  }  
}
?> 

<!DOCTYPE html>
<html>
<head>
  <title><?=$nametest?></title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="gentest.css">
</head>
<body>
   <?php getMainMenu(); ?>
   <form action="" method="POST" enctype="multipart/form-data"> 
     <fieldset>
       <legend><?=$nametest?></legend>
       <?php fillForm(); ?>
       <br/><input type="submit" value="Проверить ответы" name="btn_check"><br/>
     </fieldset>
   </form>
   <div class="result hidden"></div>
   <script type="text/javascript">
     /* проверка формы на клиентской стороне */
    'use strict';
    const btn = document.querySelector("[type=submit]");
    const questions=document.getElementsByTagName('input');
    btn.addEventListener('click', chkForm);
    Array.from(questions).forEach(q => {q.addEventListener('change',unsetErr)});

    function chkForm(event) {
      const area=document.getElementsByClassName('result');

      Array.from(questions).forEach(quest => chkElement(quest));
      // проверяем число ошибочных:
      const errEl=document.getElementsByClassName('error');
      if (errEl.length > 0) {
        area.value = "Внимание! Не выбраны ответы для " + errEl.length + " вопр.(выделены красным цветом). Заполните всю форму."
        event.preventDefault();
      }
    }

    function unsetErr(event) {
      event.target.parentElement.parentElement.previousElementSibling.classList.remove('error');
    }

    function chkElement(question) {
      const grp= document.getElementsByName(question.name);
      question.parentElement.parentElement.previousElementSibling.classList.
    }
   </script>
</body>
</html>