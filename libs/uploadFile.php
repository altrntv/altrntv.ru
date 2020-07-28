<?php
    require 'db.php';
    if ($_SESSION['lang'] == 'ru') { $lang=true; }       
    $filePath  = $_FILES['upload']['tmp_name'];
    $errorCode = $_FILES['upload']['error'];

    function transform2($str) {
        $str = mb_strtolower(str_replace(' ', '_', trim(preg_replace('/[^ a-zа-яё\d]/ui', '',$str ))));
        return $str;
    }

    if ($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($filePath)) {

        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
            UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
            UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
            UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
            UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
            UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
            UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
        ];

        $unknownMessage = 'При загрузке файла произошла неизвестная ошибка.';
        $outputMessage = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : $unknownMessage;
        die($outputMessage);
    }

    $fi = finfo_open(FILEINFO_MIME_TYPE);
    $mime = (string) finfo_file($fi, $filePath);
    finfo_close($fi);

    if (strpos($mime, 'image') === false) die('Можно загружать только изображения.');

    $image = getimagesize($filePath);

    $limitBytes  = 1024 * 1024 * 5;
    $limitWidth  = 1000;
    $limitHeight = 1000;

    if (filesize($filePath) > $limitBytes) {
        if ($lang) $text = 'Размер изображения не должен превышать 5 Мбайт.'; else $text = 'The image size must not exceed 5 MB.';
        messageSend(1, $text, 1);
    }
    if ($image[0] > $limitWidth || $image[1] > $limitHeight) {
        if ($lang) $text = 'Размер изображения не должна превышать 1000 на 1000 пикселей.'; else $text = 'The image size must not exceed 1000 by 1000 pixels.';
        messageSend(1, $text, 1);
    }

    $extension = image_type_to_extension($image[2]);

    switch ($extension) {
        case ".png":
            $format = str_replace('png', 'jpg', $extension);
            break;
        case ".jpeg":
            $format = str_replace('jpeg', 'jpg', $extension);
            break;
        case ".gif":
            $format = str_replace('gif', 'jpg', $extension);
            break;
    }

    switch ($_POST['operation']) {
        case "avatar":
            $name = md5($_SESSION['logged_user']->username);
            
            if (!move_uploaded_file($filePath, '../img/avatar/' . $name . $format)) {
                if ($lang) $text = 'При записи изображения произошла ошибка.'; else $text = 'An error occurred while recording the image.';
                messageSend(1, $text, 1);
            } else {
                header("Location: ".$_SERVER['HTTP_REFERER']);
                die();
            }
            
            break;
        case "image_song":
            $id = $_SESSION['logged_user']->id;
            $author = translit($_SESSION['logged_user']->username);
            $name = translit($_POST['songname']);
            $album = $_POST['songalbum'];
            $feat = translit($_POST['feat']);
            $genre = $_POST['genre'];
            $cca3 = $_POST['cca3'];
            $cca3 ? $cca3 : $cca3 = 0;
            $explicit = $_POST['explicit'];
            $explicit ? $explicit : $explicit = 0;
            
            if ($feat) {
                $feat = '_feat_'.transform2($feat);
                $url = transform($name).'-feat-'.transform($feat);
            } else {
                $url = transform($name);
            }
            if (!$album) {
                $album = 'Single';
            }
            
            $old_name = md5($_SESSION['logged_user']->username) . '.mp3';
            $new_name = transform2($author) . '_' . transform2($name) . $feat . '.mp3';
            rename('../temp/music/'.$old_name, '../temp/music/'.$new_name);
            
            $audioname = transform2($author) . '_' . transform2($name) . $feat . $format;
            
            R::exec('INSERT INTO confirm_songs (id_user, feat, song, album, url_song, image_song, music, cca3, explicit, genre) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$id, $_POST['feat'], $_POST['songname'], $album, $url, $audioname, $new_name, $cca3, $explicit, $genre]);

            if (!move_uploaded_file($filePath, '../temp/image/' . $audioname)) {
                if ($lang) $text = 'При записи изображения произошла ошибка.'; else $text = 'An error occurred while recording the image.';
                messageSend(1, $text, '/upload');
            } else {
                if ($lang) $text = 'Ваша аудиозапись загружена и ожидает проверки на оригинальность. После чего, вам придет уведомление о подтверждении или отклонении.'; else $text = 'Your audio recording has been uploaded and is awaiting verification for originality. After that, you will receive a notification of confirmation or rejection.';
                messageSend(3, $text, '/upload');
            }
            
            break;  
    }
?>