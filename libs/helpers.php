<?php

define('ROOT', 'https://'.$_SERVER['HTTP_HOST']);

// функции отображения сообщений
function messageSend($num, $text, $url) {
    switch ($num) {
        case 1:
            $num = 'Error';
            break;
        case 2:
            $num = 'Hint';
            break;
        case 3:
            $num = 'Information';
            break;
    }
    if ($url == 1) $url = $_SERVER['HTTP_REFERER'];
    $_SESSION['message'] = '<div class="message__block '.mb_strtolower($num).'"><b>'.$num.'</b>: '.$text.'</div>';
    exit(header('Location: '.$url));
}

function messageShow() {
    echo $_SESSION['message'] ? $_SESSION['message'] : '';
    unset($_SESSION['message']);
}

// функция, обрабатывающая входящие строки, заменяет все символы на тире
function transform($str) {
    $str2 = '';
    $array = str_split($str);
    foreach($array as $el) {
        if(ctype_alnum($el) || $el == ' ') $str2 .= $el;
        else $str2 .= ' ';
    }
    $str3 = preg_replace('/^ +| +$|( ) +/m', '$1', $str2);
    $str3 = mb_strtolower(str_replace(' ', '-', $str3));
    $str3 = str_replace('039', '', $str3);
    return $str3;
}

function today() {
    $_monthsList = array(".01." => "January", ".02." => "February", ".03." => "March", 
                     ".04." => "April", ".05." => "May", ".06." => "June", 
                     ".07." => "July", ".08." => "August", ".09." => "September",
                     ".10." => "October", ".11." => "November", ".12." => "December");
    $_mD = date(".m.");

    $date = date(".m. j, Y");

    $date = str_replace($_mD, $_monthsList[$_mD], $date);
    return $date;
}

// функция, которая считает количество прошедшего времени от какой-то даты
function showDate ($time) {

    $time = time() - $time;
    $time = ($time<1)? 1 : $time;

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }
}

// функция проверки изображения, существуюи ли они на сервере или нет
function image($url) {
    $headers = get_headers($url);

    // проверяем ли ответ от сервера с кодом 200 - ОК
    if(strpos($headers[0], '200')) {
        return 'background-image: url('.$url.');';
    }
}

// функция отправки уведемлений на сервер
function notifications($id_recipient, $id_sender, $text) {
    R::exec('INSERT INTO notifications (id_recipient, id_sender, text) VALUES(?, ?, ?)', [$id_recipient, $id_sender, $text]);
}

// функция рекомендаций, работает по Коллаборативной фильтрации
function recommended() {
    $id = $_SESSION['logged_user']->id;
    $count = R::count( 'users' );
    
    $likeUser = R::getCol("SELECT id_song FROM favourite_songs WHERE id_user = ?", [$id]);
    $likeAll = R::getAll("SELECT * FROM favourite_songs WHERE id_user <> ?", [$id]);
    if ($likeUser) {
        $same = array();
        foreach ($likeUser as $lu) {
            foreach ($likeAll as $la) {
                if ($la['id_song'] == $lu) $same[] = $la['id_user'];
            }
        }
        $array = array_count_values($same);

        $result = array();

        foreach ($array as $key => $value) {
            if ($value >= 3) $result[] = $key;
        }
        if ($result) {
            $final = R::getCol("SELECT id_song FROM favourite_songs WHERE id_user IN (". R::genSlots($result) .")", $result);
            $final = array_unique($final);
            $final = array_values(array_diff($final, $likeUser));
            return $final;
        } else {
            return false;
        }
    }
}

// функция переводящая русские буквы на латинские 
function translit($str) {
  $str = (string) $str; // преобразуем в строковое значение
  $str = trim($str); // убираем пробелы в начале и конце строки
  $str = function_exists('mb_strtolower') ? mb_strtolower($str) : strtolower($str); // переводим строку в нижний регистр (иногда надо задать локаль)
  $str = strtr($str, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  return $str; // возвращаем результат
}