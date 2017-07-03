<?php
include 'lib.php';
readDest($dest);
$jsonData=[];

// обработка get-запроса
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
  $nametest=$jsonData[0]['Название'];
  $jsonData=$jsonData[0]['Вопросы'];
}

// обработка post-запроса
$results=[];
$nom=1;
if ($_POST) {
  foreach($jsonData as $key => $question) { // перебираем вопросы, есть ли ответ на вопрос?
    if (! array_key_exists($key, $_POST)) {
      $results[]="$nom. Ответ не предоставлен. Правильный ответ: ".$jsonData[$key]['Ответ'];
      continue;
    }
    if (is_array($_POST[$key])) {
      $ans=implode($_POST[$key],","); // чекбоксы собираем из массива
    } else {
      $ans=$_POST[$key]; // радиокнопки - обычное значение
    }
    if ($jsonData[$key]['Ответ'] != $ans) {
      $results[]="$nom. Ответ неверный. Ваш ответ: $ans. Правильный ответ: ".$jsonData[$key]['Ответ'];
    } else {
      $results[]="$nom. Ответ $ans верный";
    }
    $nom++;
  }
  echo "<pre>".implode($results,"\n")."</pre>";
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
    $nom=++$key;
    echo "<label class=\"question\">$nom. $question</label>";
    echo "<ul class=\"answers\">";
    if (count(explode(",", $r_answer)) > 1) {
      foreach ($answers as $key => $ans) {
        echo "<li><input type=\"checkbox\" name=\"$name"."[]"."\" value=\"$key\" id=\"$name$key\"/><label for=\"$name$key\">$ans</label></li>";
      }  
    } else {
      foreach ($answers as $key => $ans) {
        echo "<li><input type=\"radio\" name=\"$name\" value=\"$key\" id=\"$name$key\"/><label for=\"$name$key\">$ans</label></li>";
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
   <?php echo getMainMenu(); ?>
   <form>
   <fieldset class='hidden'>
      <legend>Результат</legend>
      <output class='farea'><?php echo "<pre>".implode($results,"\n")."</pre>"; ?></output>
   </fieldset>
   </form>
   <form action="" method="POST" enctype="multipart/form-data" class="mainform"> 
     <fieldset>
       <legend><?=$nametest?></legend>
       <?php fillForm(); ?>
       <br/><input type="submit" value="Проверить ответы" name="btn_check"><br/>
     </fieldset>
   </form>
   <script type="text/javascript">
     /* проверка формы на клиентской стороне */
   'use strict';
    const btn = document.querySelector("[type=submit]");
    const ans = document.querySelectorAll("[type=input]");
    const quests=document.getElementsByClassName('question');
    const output=document.querySelector('output');

    btn.addEventListener('click', chkForm);
    Array.from(ans).forEach(a => {a.addEventListener('change',undetErr)});

    //--проверка формы средствами JS:
    function chkForm(event) {
      /*event.preventDefault();*/
      const fldset=document.querySelector('fieldset.hidden');
      if (fldset) { fldset.classList.remove('hidden'); }
      Array.from(quests).forEach(quest => chkElement(quest)); // проверяем каждый вопрос, выбран ли ответ
      const errEl=document.getElementsByClassName('error');
      
      if (errEl.length > 0) {
        output.textContent = "Внимание! Не выбраны ответы для " + errEl.length + " вопр.(выделены красным цветом). Заполните всю форму.";
        event.preventDefault();
      } else { /*
        const form=document.getElementsByClassName('mainform')[0];
        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        xhr.addEventListener('load', (e) => {
           console.log(e.results);
           console.log(this.response);
           console.log(this.responseText);
           output.textContent=e.results;
        });
        xhr.open('POST', form.getAttribute('action'), true);
        xhr.setRequestHeader('Content-Type', form.getAttribute('enctype'));
        xhr.send(formData);*/
      }
    }

    function unsetErr(event) {
      event.target.parentElement.parentElement.previousElementSibling.classList.remove('error');
    }

    function chkElement(quest) {
      const li=quest.nextElementSibling.firstChild.firstChild.getAttribute('name');
      const grp=document.getElementsByName(li);
      let chked=Array.from(grp).filter(g => { return g.checked; }); 
      if (chked.length == 0) {
        quest.classList.add('error');
      }
      else {
        quest.classList.remove('error');
      }
    }
   </script>
</body>
</html>