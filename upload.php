<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
if ($_SESSION['logged_user']) {
    echo '
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>'; echo $lang ? 'Загрузка аудиозаписи' : 'Upload music'; echo '</title>
            <link rel="stylesheet" href="'.ROOT.'/css/common.css">
            <link rel="stylesheet" href="'.ROOT.'/css/upload.css">
            <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
        </head>
        <body>
            <div id="fullpage">';
                require 'libs/header.php';
    echo       '<div class="content">';
                    messageShow();
    echo            '<div class="upload__content">
                        <div class="form">';
                            if ($_GET['audio'] == 'ok') {
                                echo '
                                <form class="upload-form" action="libs/uploadFile" method="post" enctype="multipart/form-data">
                                    <div class="flex-info">
                                        <div class="info">
                                            <div class="upload-group">
                                                <label for="songname">'; echo $lang ? 'Название аудиозаписи' : 'Song name'; echo '</label>
                                                <input type="text" name="songname" value="" required="required">
                                            </div>
                                            <div class="upload-group">
                                                <label for="songalbum">'; echo $lang ? 'Название альбома (необязательное)' : 'Song album (optional)'; echo '</label>
                                                <input type="text" name="songalbum" value="">
                                            </div>
                                            <div class="upload-group">
                                                <label for="feat">'; echo $lang ? 'Совместный исполнитель (необязательное)' : 'Feat (optional)'; echo '</label>
                                                <input type="text" name="feat" value="">
                                            </div>
                                            <div class="upload-group">
                                                <label for="genre">'; echo $lang ? 'Жанр' : 'Genre'; echo '</label>';
                                                $genres = R::findCollection('genres', 'ORDER BY genre');
                                                echo '<select name="genre">';
                                                while ($row = $genres->next()) {
                                                    echo '<option value = '.$row["id"].'> '.$row["genre"].' </option>';
                                                }
                                                echo '</select>';
                                    echo   '</div>
                                            <div class="upload-group">
                                                <label class="container cbox">
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="cca3" value="1">
                                                        <span class="checkmark"></span>
                                                    </div>
                                                    <span>Creative Commons License</span>
                                                </label>
                                            </div>
                                            <div class="upload-group">
                                                <label class="container cbox">
                                                    <div class="checkbox">
                                                        <input type="checkbox" name="explicit" value="1">
                                                        <span class="checkmark"></span>
                                                    </div>
                                                    <span>'; echo $lang ? 'Ненормативная лексика' : 'Profanity'; echo '</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="upload-button">
                                            <div class="form-group">
                                                <label class="label">
                                                <i class="fas fa-paperclip"></i>
                                                <span id="file-1" class="title">'; echo $lang ? 'Выберете обложку' : 'Upload image'; echo '</span>
                                                <input type="file" accept="image/*,image/jpeg" class="file" name="upload" required="required">
                                                <input type="hidden" name="operation" value="image_song"/>
                                                </label>
                                            </div>
                                            <div class="preview">
                                                <output id="output"></output>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit" style="justify-content: flex-end;">
                                        <input class="button" type="submit" value="'; echo $lang ? 'Загрузить' : 'Upload'; echo '">
                                    </div>
                                </form>';
                            } else {
                                echo '
                                <form class="upload-form" action="libs/uploadAudio" method="post" enctype="multipart/form-data">
                                    <div class="upload-button">
                                        <div class="form-group">
                                            <label class="label">
                                            <i class="fas fa-paperclip"></i>
                                            <span id="file-1" class="title">'; echo $lang ? 'Выберете файл' : 'Upload audio'; echo '</span>
                                            <input type="file" accept="audio/mpeg" class="file" name="upload" required="required">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="submit" style="justify-content: center;">
                                        <input class="button" type="submit" value="'; echo $lang ? 'Загрузить' : 'Upload'; echo '">
                                    </div>
                                </form>';
                            }
                            echo '
                        </div>
                    </div>
                </div>
            </div>
            <footer class="min-footer"><div>ALTRNTV</div></footer>
        </body>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="'.ROOT.'/js/script.js"></script>
        <script src="'.ROOT.'/js/upload.js"></script>
    </html>';
} else {
    header("Location: /");
    die();
}
?>