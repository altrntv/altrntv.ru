<?php
    require 'db.php';
    
    $data = $_POST;
    if (isset($_GET["theme"])) {
        $theme = $_GET["theme"];
        if ($theme == "light" || $theme == "dark") {
            $_SESSION["theme"] = $theme;
        }
    } else if (isset($data['lang'])) {
        $_SESSION['lang'] = $data['lang'];
        header("Location: ".$_SERVER['HTTP_REFERER']);   
    } else if (isset($data['do_url'])) {
        $user = R::load('users', $_SESSION['logged_user']->id);
        $user->url = $data['url'];
        R::store($user);
        $_SESSION['logged_user']->url = $data['url'];
        header("Location: ".$_SERVER['HTTP_REFERER']);
    } else if (isset($data['do_username'])) {
        $user = R::load('users', $_SESSION['logged_user']->id);
        $user->username = $data['username'];
        R::store($user);
        $avatar = get_headers(ROOT.'/img/avatar/'.md5($_SESSION['logged_user']->username).'.jpg');
        if(strpos($avatar[0], '200')) {
            rename('../img/avatar/'.md5($_SESSION['logged_user']->username).'.jpg', '../img/avatar/'.md5($data['username']).'.jpg');
        }
        $cap = get_headers(ROOT.'/img/cap/'.md5($_SESSION['logged_user']->username).'.jpg');
        if(strpos($cap[0], '200')) {
            rename('../img/cap/'.md5($_SESSION['logged_user']->username).'.jpg', '../img/cap/'.md5($data['username']).'.jpg');
        }
        $_SESSION['logged_user']->username = $data['username'];
        header("Location: ".$_SERVER['HTTP_REFERER']);
    } else if (isset($data['do_email'])) {
        $user = R::load('users', $_SESSION['logged_user']->id);
        $user->email = $data['email'];
        R::store($user);
        $_SESSION['logged_user']->email = $data['email'];
        header("Location: ".$_SERVER['HTTP_REFERER']);
    } else if (isset($data['do_password'])) {
        $user = R::load('users', $_SESSION['logged_user']->id);
        if (password_verify($data['oldpassword'], $user->password)) {
            if ($data['newpassword'] == $data['repeatpassword']) {
                $user->password = password_hash($data['newpassword'], PASSWORD_DEFAULT);
                R::store($user);
                $_SESSION['logged_user']->password = $data['newpassword'];
                header("Location: ".$_SERVER['HTTP_REFERER']);
            } else {
                echo 'Пароли не совпадают';
            }
        } else {
            echo 'Неверный пароль';
        }
    } else {
        header("Location: /");
        die();
    }
?>