<?php
require 'libs/db.php';

if (isset($_GET['q'])){
    $search = $_GET['q'];

    $user = R::getAll('SELECT username, url FROM users WHERE username LIKE ?', ["%$search%"]);
    $album = R::getAll('SELECT albums.*, users.username, users.url FROM albums INNER JOIN users ON users.id=albums.id_user WHERE users.username LIKE :search OR albums.album LIKE :search', ['search' => "%$search%"]);
    $song = R::getAll('SELECT songs.*, users.username, users.url FROM songs INNER JOIN users ON users.id=songs.id_user WHERE users.username LIKE :search OR songs.song LIKE :search', ['search' => "%$search%"]);
    echo '
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="'.ROOT.'/css/common.css">
            <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.0/css/all.css">
            <title>Search</title>
        </head>
        <body>
            <div id="fullpage">';
                require 'libs/header.php';
                echo '
                <div class="content">
                    <div class="search__content" style="padding-bottom: 100px;">
                        <h2 style="text-align: center; margin: 0; padding: 20px;">Search results for «'.$search.'»</h2>
                        <div class="result" style="margin: 0 30px;">';
                            if ($user) {
                                echo '
                                <h3>Users</h3>
                                <div class="users">';
                                foreach($user as $row) {
                                    echo '
                                    <div class="list__item">
                                        <a href="'.ROOT.'/'.$row['url'].'">
                                            <div class="song__image">
                                                <span class="avatar-image" style="width: 100px; height: 100px; '.image(ROOT.'/img/avatar/'.md5($row['username']).'.jpg').'"></span>
                                            </div>

                                            <div class="song__body">
                                                <div class="song__username">'.$row['username'].'</div>
                                            </div>
                                        </a>
                                    </div>';
                                }
                                echo '
                                </div>';
                            }
                            if ($album) {
                                echo '
                                <h3>Album</h3>
                                <div class="album">';
                                foreach($album as $row) {
                                    echo '
                                    <div class="list__item">
                                        <a href="'.ROOT.'/'.$row['url'].'/album/'.$row['url_album'].'">
                                            <div class="song__image">
                                                <img src="/img/album/'.$row['image_album'].'" class="song__img">
                                            </div>

                                            <div class="song__body">
                                                <div class="song__name"><b><i class="fas fa-compact-disc"></i> '.$row['album'].'</b> <span title="'.$row['date'].'">'.showDate(strtotime($row['date'])).' ago</span></div>
                                                <div class="song__username">'.$row['username'].'</div>
                                            </div>
                                        </a>
                                    </div>';
                                }
                                echo '
                                </div>';
                            }
                            if ($song) {
                                echo '
                                <h3>Songs</h3>
                                <div class="songs">';
                                foreach($song as $row) {
                                    echo '
                                    <div class="list__item">
                                        <a href="'.ROOT.'/'.$row['url'].'/'.$row['url_song'].'">
                                            <div class="song__image">
                                                <img src="/img/music/'.$row['image_song'].'" class="song__img">
                                            </div>

                                            <div class="song__body">
                                                <div class="song__name"><b>'.$row['song'].'</b> <span title="'.$row['date'].'">'.showDate(strtotime($row['date'])).' ago</span></div>
                                                <div class="song__username">'.$row['username']; if ($row['feat']) { echo ' <span class="feat">feat.</span> '.$row['feat']; } echo '</div>
                                                <div class="song__album"><i class="fas fa-compact-disc"></i> '.$row['album'].'</div>
                                            </div>
                                        </a>
                                    </div>';
                                }
                                echo '
                                </div>';
                            }
                            if (!$user && !$album && !$song) {
                                echo '
                                <div class="search__item">
                                    <p>Nothing found</p>
                                </div>';
                            }
                            echo '
                        </div>
                    </div>
                </div>
            </div>
            <footer class="min-footer"><div>ALTRNTV</div></footer>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="'.ROOT.'/js/script.js"></script>
        </body>
    </html>';    
} else {
    header("Location: /");
    die();
}
?>