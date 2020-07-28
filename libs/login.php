<?php
    require 'db.php';
    if ($_SESSION['lang'] == 'ru') { $lang=true; }
    $data = $_POST;

    if(isset($data['do_login'])) {
        
        $errors = true;
        $user = R::findOne('users', 'email = ?', array($data['email']));
        
        if ($user) {
            if (password_verify($data['password'], $user->password)) {
                $_SESSION['logged_user'] = $user;
            } else {
                if ($lang) $text = 'Неправильный пароль'; else $text = 'Wrong password';
                messageSend(1, $text, 1);
                $errors = false;
            }
        } else {
            if ($lang) $text = 'Пользователь не найден'; else $text = 'The user is not found';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if ($errors) {
            header('Location: /');
        }
    } else {
        header('Location: /');
    }
?>