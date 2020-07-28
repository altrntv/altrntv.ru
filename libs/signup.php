<?php
    require 'db.php';
    if ($_SESSION['lang'] == 'ru') { $lang=true; }
    $data = $_POST;

    if(isset($data['do_signup'])) {
        
        $errors = true;
        if (trim($data['name']) == '') {
            if ($lang) $text = 'Введите имя'; else $text = 'Enter a name';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if (trim($data['email']) == '') {
            if ($lang) $text = 'Введите E-mail'; else $text = 'Enter E-mail Address';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if ($data['password'] == '') {
            if ($lang) $text = 'Введите пароль'; else $text = 'Enter the password';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if ($data['password_confirmation'] != $data['password']) {
            if ($lang) $text = 'Повторный пароль введен не верно'; else $text = 'Re-entered password is not correct';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if (R::count('users', 'url = ?', array(transform(translit(trim($data['name']))))) > 0) {
            if ($lang) $text = 'Пользователь с таким именем уже существует'; else $text = 'A user with this name already exists';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if (R::count('users', 'email = ?', array(trim($data['email']))) > 0) {
            if ($lang) $text = 'Пользователь с таким E-mail уже существует'; else $text = 'A user with this E-mail already exists';
            messageSend(1, $text, 1);
            $errors = false;
        }
        
        if ($errors) {
            $token=md5($data['email'].time());
            $user = R::dispense('cusers');
            $user->name = trim($data['name']);
            $user->email = $data['email'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->token = $token;
            R::store($user);
            
            $message = '
            <p>Hello, '.$data['name'].'!</p><p>To complete registration, you must confirm your email address.</p>
            <p><a href="https://altrntv.ru/libs/activation?token='.$token.'">http://altrntv.ru/libs/activation?token='.$token.'</a></p>';
            $headers = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: support@altrntv.ru';
            
            mail($data['email'], 'Confirm email address', $message, $headers);
            if ($lang) $text = 'Для завершения регистрации требуется подтвердить адрес электронной почты. Ссылка для подтверждения адреса электронной почты была отправлена на '.$data['email']; else $text = 'To complete registration, you must confirm your email address. A link to confirm your email address was sent to '.$data['email'];
            messageSend(3, $text, 1);
        }
        
    } else {
        header('Location: /');
    }
?>