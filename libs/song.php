<?php
require 'db.php';
$message = '';
$error = true;
if (intval($_POST['id']) && $_POST['like']) { //запрос обработывающий лайки
    $id = intval($_POST['id']);
    $like = $_POST['like'];
    $user = $_SESSION['logged_user']->id;

    if ($id && $user && $like == "false") {
        R::exec('INSERT INTO favourite_songs (id_song, id_user) VALUES(?, ?)', [$id, $user]);
        $error = false;
        $message = 'success';
    } else if ($id && $like == "true") {
        R::exec('DELETE FROM favourite_songs WHERE id_song=? AND id_user=?', [$id, $user]);
        $error = false;
        $message = 'success';
    } else {
       $error = true;
       $message = 'Error';
    }

    /** Возвращаем ответ скрипту */
    // Формируем масив данных для отправки
    $out = array(
        'error' => $error,
        'message' => $message,
    );
    // Устанавливаем заголовот ответа в формате json
    header('Content-Type: text/json; charset=utf-8');
    // Кодируем данные в формат json и отправляем
    echo json_encode($out);    
} else if (intval($_POST['id']) && intval($_POST['score']) && $_POST['text']) { // запрос обрабатывающий обзоры
    $id = intval($_POST['id']);
    $score = intval($_POST['score']);
    $text = str_replace("'", '&#039', $_POST['text']);
    $user = $_SESSION['logged_user']->id;

    if ($id && $score && $text && $user) {
        R::exec('INSERT INTO reviews (id_song, id_user, user_score, review) VALUES(?, ?, ?, ?)', [$id, $user, $score, $text]);
        $error = false;
        $recipient = (int)R::getCell('SELECT `id_user` FROM songs WHERE `id` = ? LIMIT 1', [$id]);
        notifications($recipient, $user, 'review-'.$id);
    } else{
       $error = true;
       $message = 'Error';
    }

    /** Возвращаем ответ скрипту */
    // Формируем масив данных для отправки
    $out = array(
        'error' => $error,
        'message' => $message,
    );
    // Устанавливаем заголовот ответа в формате json
    header('Content-Type: text/json; charset=utf-8');
    // Кодируем данные в формат json и отправляем
    echo json_encode($out);   
} else if (intval($_POST['id']) && $_POST['review']) { // удаление обзоров
    $id = intval($_POST['id']);
    $review = $_POST['review'];

    if ($id && $review == "delete") {
        R::exec('DELETE FROM reviews WHERE id=?', [$id]);
        $error = false;
        $message = 'success';
    } else {
       $error = true;
       $message = 'Error';
    }

    /** Возвращаем ответ скрипту */
    // Формируем масив данных для отправки
    $out = array(
        'error' => $error,
        'message' => $message,
    );
    // Устанавливаем заголовот ответа в формате json
    header('Content-Type: text/json; charset=utf-8');
    // Кодируем данные в формат json и отправляем
    echo json_encode($out);
} else {
    header("Location: /");
    die();
}

?>