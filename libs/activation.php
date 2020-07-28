<?php
require 'db.php';
if ($_SESSION['lang'] == 'ru') { $lang=true; }
if(isset($_GET['token']) && !empty($_GET['token'])){

    $token = $_GET['token'];
    $cuser = R::getRow('SELECT * FROM `cusers` WHERE `token` = ? LIMIT 1', [ $token ]);

    //Если пользователь найден
    if($cuser) {
        $user = R::dispense('users');
        $user->username = trim($cuser['name']);
        $user->email = $cuser['email'];
        $user->password = $cuser['password'];
        $user->url = transform(translit(trim($cuser['name'])));
        R::store($user);
        
        R::exec('DELETE FROM cusers WHERE token = ?', [ $token ]);
		
      	if ($lang) $text = 'Регистрация прошла успешно!'; else $text = 'Registration was successful!';
        messageSend(3, $text, 'https://altrntv.ru/login');
        //exit("<p><strong>Thank you for registering!</strong></p>");

    } else { //if($cuser)
        //Иначе, если есть ошибки в запросе к БД
        exit("<p><strong>Error!</strong> Сбой при выборе пользователя из БД. </p>");
    }

} else {
    exit("<p><strong>Error!</strong> Неправильный проверочный код.</p>");
}
?>