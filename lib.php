<?php 
 
$dest="tests".DIRECTORY_SEPARATOR; // директория сфайлами теста
$pattern="/^[0-9a-z]*.json$/"; // маска валидных файлов
$filelist=[]; // список файлов

// функция читает директорию с тестами
function readDest($destination) {
  $cdir=scandir($destination);
  global $filelist;
  global $pattern;

  foreach ($cdir as $key => $value) { 
    if (! is_dir($value)) {
      preg_match($pattern, $value, $matches);
      if (count($matches) < 1) { continue; }
      $filelist[]=$value;
    } 
  }
}

// функция возвращает удобочитаемое название по имени файла
function getTestName($test) {
  switch ($test) {
      case 'astrology':
        $test_name="Астрология";
        break;
      case 'astronomy':
        $test_name="Астрономия";
        break;
      case 'philosophy':
        $test_name="Философия";
        break;
      default:
        $test_name="Неизвестный тест";
        break;
  }
  return $test_name;
}

// функция отстраивает главное меню
function getMainMenu() {
  echo "<ul class=\"menu\">
        <li><a href=\"admin.php\">Администриование</a></li>
        <li><a href=\"list.php\">Список тестов</a></li>
        </ul>";
}

?>
