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
$results=[];
$num=0;
if (isset($_POST['btn_check'])) {
  foreach($_POST as $key => $ans) {
    if (!is_numeric($key)) { continue; }
    $num=++$num;
    if (is_array($ans)) {$ans=implode($ans,",");} // чекбоксы собираем из массива
    if ($jsonData[$key]['Ответ'] != $ans) {
      $results[]="$num. Ответ неверный. Правильный ответ ".$jsonData[$key]['Ответ'];
    } else {
      $results[]="$num. Ответ верный";
    }
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
        echo "<li><input type=\"checkbox\" name=\"$name"."[]"."\" value=\"$key\"/>$ans</li>";
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
   <form>
   <fieldset class='hidden'>
      <legend>Результат</legend>
      <output class='farea'></output>
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
      const fldset=document.querySelector('fieldset.hidden');
      if (fldset) { fldset.classList.remove('hidden'); }
      Array.from(quests).forEach(quest => chkElement(quest)); // проверяем каждый вопрос, выбран ли ответ
      const errEl=document.getElementsByClassName('error');
      
      if (errEl.length > 0) {
        output.textContent = "Внимание! Не выбраны ответы для " + errEl.length + " вопр.(выделены красным цветом). Заполните всю форму.";
        event.preventDefault();
      } else {
        /*
        const form=document.getElementsByClassName('mainform')[0];
        const xhr = new XMLHttpRequest();
        const formData = new FormData(form);
        xhr.addEventListener('load', (e) => {
           output.textContent=e.results;
        });
        xhr.open('POST', '');
        xhr.send(formData);
        */
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