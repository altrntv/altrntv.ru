<?php
    require 'db.php';
    header("Content-Type:text/html;charset=utf-8");
    
    $filePath  = $_FILES['upload']['tmp_name'];
    $errorCode = $_FILES['upload']['error'];
    
    $name = md5($_SESSION['logged_user']->username);

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
    
    if (strpos($mime, 'audio/mpeg') === false) die('Можно загружать только mp3.');

    $limitBytes  = 1024 * 1024 * 15;

    if (filesize($filePath) > $limitBytes) die('Размер аудиозаписи не должен превышать 15 Мбайт.');
    
    $music = $name.'.mp3';

    if (!move_uploaded_file($filePath, '../temp/music/' . $music)) {
        die('При записи аудиозаписи на диск произошла ошибка.');
    } else {
        header("Location: /upload?audio=ok");
        die();
    }
?>