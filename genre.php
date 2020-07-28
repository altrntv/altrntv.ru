<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
$genre = R::getRow('SELECT * FROM `genres` WHERE `genre` = ? LIMIT 1', [ $_GET['genre'] ]);
$songs = R::getAll('SELECT songs.*, DATE_FORMAT(songs.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM songs INNER JOIN users ON songs.id_user=users.id WHERE songs.genre=? ORDER BY songs.date DESC', [$genre['id']]);

echo '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.$_GET['genre'].'</title>
    <link rel="stylesheet" href="'.ROOT.'/css/common.css">
    <link rel="stylesheet" href="'.ROOT.'/css/genre.css">
    <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.0/css/all.css">
</head>
<body>
    <div id="fullpage">';
    require 'libs/header.php';
echo    '<div class="content">
            <div class="genre__content">
                <div class="genre__left">
                    <div class="genre__name">'.$genre['genre'].'</div>
                </div>

                <div class="genre__right">
                    <div class="list">';
                        if ($songs) {
                            foreach($songs as $key) {
                                echo '
                                <div class="list__item">
                                    <a href="'.ROOT.'/'.$key['url'].'/'.$key['url_song'].'">
                                        <div class="song__image">
                                            <img src="/img/music/'.$key['image_song'].'" class="song__img">
                                        </div>

                                        <div class="song__body">
                                            <div class="song__name"><b>'.$key['song'].'</b> <span title="'.$key['date'].'">'.showDate(strtotime($key['date'])).' ago</span></div>
                                            <div class="song__username">'.$key['username']; if ($key['feat']) { echo ' <span class="feat">feat.</span> '.$key['feat']; } echo '</div>
                                            <div class="song__album"><i class="fas fa-compact-disc"></i> '.$key['album'].'</div>
                                        </div>
                                    </a>
                                </div>';
                            }   
                        } else {
                            echo '
                            <div class="nomusic">'; echo $lang ? 'Аудиозаписи такого жанра отсутствуют' : 'There are no audio recordings of this genre'; echo '</div>';
                        }

                    echo '
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="min-footer"><div>ALTRNTV</div></footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="'.ROOT.'/js/script.js"></script>
</body>
</html>';
?>