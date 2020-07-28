<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
$user = R::findOne('users', 'url = ?', array($_GET['login']));
$songs = R::getAll('SELECT songs.*, DATE_FORMAT(songs.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM songs INNER JOIN users ON songs.id_user=users.id WHERE songs.id_user=? OR songs.feat=? ORDER BY songs.date DESC', array($user->id, $user->username));
$albums = R::getAll('SELECT albums.*, DATE_FORMAT(albums.date, "%M %d, %Y") as date, users.username, users.url FROM albums INNER JOIN users ON albums.id_user=users.id WHERE albums.id_user=? ORDER BY albums.date DESC', array($user->id));
$favourite = R::getAll('SELECT songs.*, DATE_FORMAT(songs.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM favourite_songs INNER JOIN songs ON favourite_songs.id_song=songs.id INNER JOIN users ON songs.id_user=users.id WHERE favourite_songs.id_user=?', array($user->id));
$reviews = R::getAll('SELECT reviews.user_score, songs.song, songs.feat, users.username, users.url FROM reviews INNER JOIN songs ON reviews.id_song = songs.id INNER JOIN users ON songs.id_user=users.id WHERE reviews.id_user=? ORDER BY reviews.id DESC LIMIT 3', array($user->id));

echo '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'; echo $user->username ? $user->username : 'Unknown User'; echo '</title>
    <link rel="stylesheet" href="'.ROOT.'/css/common.css">
    <link rel="stylesheet" href="'.ROOT.'/css/profile.css">
    <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
</head>
<body>
    <div id="fullpage">';
        require 'libs/header.php';
        echo '
        <div class="connect">';
            if($user) {
                echo '
                <div class="cap">
                    <div class="cap__image" style="'.image(ROOT.'/img/cap/'.md5($user->username).'.jpg').'"></div>
                </div>
                <div class="profile__user">';
                    messageShow();
                    echo '
                    <div class="profile-header">
                        <div class="profile">
                            <div class="profile-avatar">
                                <span class="avatar-image" style="'.image(ROOT.'/img/avatar/'.md5($user->username).'.jpg').'">';
                                    if ($user->username == $_SESSION['logged_user']->username) {
                                        echo '<button id="reset-image"><i class="fas fa-times"></i></button>
                                    <form id="image-form" action="libs/uploadFile.php" method="post" enctype="multipart/form-data">
                                        <div class="upload-image">
                                            <input id="file-input" type="file" name="upload" accept="image/*,image/jpeg">
                                            <label for="file-input"><i class="fas fa-image"></i></label>
                                            <input type="hidden" name="operation" value="avatar"/>
                                            <button class="upload-image-button"><i class="fas fa-check"></i></button>
                                        </div>
                                    </form>';   
                                    }
                                echo '</span>
                            </div>
                            <div class="profile-info">
                                <div>
                                    <h1>'.$user->username; if ($user->confirmed == 1) { echo '<span style="color: lightblue; margin-left: 10px;"><i class="fas fa-check"></i></span>'; } echo '</h1>';
                                    if ($user->gold == 1) {
                                        echo '<div class="user-gold">GOLD</div>';
                                    }
                                echo '</div>
                            </div>
                        </div>
                    </div>
                    <div class="content-music">
                        <div id="app" class="profile__music">
                            <ul class="music__menu">
                                <li class="menu-item" @click="menu = 0" :class="{active: menu === 0}">'; echo $lang ? 'Музыка' : 'Music'; echo '</li>
                                <li class="menu-item" @click="menu = 1" :class="{active: menu === 1}">'; echo $lang ? 'Альбомы' : 'Album'; echo '</li>
                                <li class="menu-item" @click="menu = 2" :class="{active: menu === 2}">'; echo $lang ? 'Понравившиеся' : 'Favourite'; echo '</li>
                            </ul>
                            <div class="box" v-if="menu === 0">';
                            if ($user->username == $_SESSION['logged_user']->username) {
                                echo '
                                <a class="upload-music" href="'.ROOT.'/upload">
                                    <span><i class="fas fa-upload"></i></span>
                                    <span>'; echo $lang ? 'Загрузить музыку' : 'Upload music'; echo '</span>
                                </a>';
                            }
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
                                <div class="nomusic">
                                    <i class="fas fa-address-card"></i>
                                    <span>'; echo $lang ? 'Здесь ничего нет' : 'There&#039s nothing here'; echo '</span>
                                </div>';
                            }
                            echo '
                            </div>
                            <div class="box" v-else-if="menu === 1">';
                            if ($user->username == $_SESSION['logged_user']->username) {
                                echo '
                                <a class="create-album" href="'.ROOT.'/create">
                                    <span><i class="fas fa-folder-plus"></i></span>
                                    <span>'; echo $lang ? 'Создать альбом' : 'Create album'; echo '</span>
                                </a>';
                            }
                            if ($albums) {
                                foreach($albums as $key) {
                                echo '
                                <div class="list__item">
                                    <a href="'.ROOT.'/'.$key['url'].'/album/'.$key['url_album'].'">
                                        <div class="song__image">
                                            <img src="/img/album/'.$key['image_album'].'" class="song__img">
                                        </div>

                                        <div class="song__body">
                                            <div class="song__name"><b><i class="fas fa-compact-disc"></i> '.$key['album'].'</b> <span>'.$key['date'].'</span></div>
                                            <div class="song__username">'.$key['username'].'</div>
                                        </div>
                                    </a>
                                </div>';
                                }    
                            } else {
                                echo '
                                <div class="nomusic">
                                    <i class="fas fa-address-card"></i>
                                    <span>'; echo $lang ? 'Здесь ничего нет' : 'There&#039s nothing here'; echo '</span>
                                </div>';
                            }
                            echo '
                            </div>
                            <div class="box" v-else-if="menu === 2">';
                            if ($favourite) {
                                foreach($favourite as $key) {
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
                                <div class="nomusic">
                                    <i class="fas fa-address-card"></i>
                                    <span>'; echo $lang ? 'Здесь ничего нет' : 'There&#039s nothing here'; echo '</span>
                                </div>';
                            }
                            
                            echo '
                            </div>
                        </div>
                        <div class="more">
                            <div class="stats">
                                <div>
                                    <span class="icon basic"><i class="far fa-chart-bar"></i>'; echo $lang ? 'Статистика' : 'Stats'; echo '</span>
                                </div>
                                <div>
                                    <span class="icon"><i class="fas fa-music"></i>'; echo $lang ? 'Песен: ' : 'Music: '; echo count($songs); echo '</span>
                                </div>
                                <div>
                                    <span class="icon"><i class="fas fa-compact-disc"></i>'; echo $lang ? 'Альбомов: ' : 'Album: '; echo count($albums); echo '</span>
                                </div>
                            </div>
                            <div class="comments">
                                <div>
                                    <span class="icon basic"><i class="fas fa-comment"></i>'; echo $lang ? 'Недавние обзоры' : 'Recent reviews'; echo '</span>
                                </div>';
                                if ($reviews) {
                                    foreach($reviews as $key) {
                                        echo '
                                        <div class="review__block">
                                            <div class="comment-song">'; echo $lang ? 'на ' : 'on '; echo '<a href="/'.$key['url'].'/'.transform($key['song']); echo $key['feat'] ? '-feat-'.transform($key['feat']) : ''; echo '">'.$key['username'].' - '. $key['song']; if ($key['feat']) echo ' <span class="feat">feat.</span> '.$key['feat']; echo '</a></div>
                                            <div class="comment-text">'; echo $lang ? 'Оценка: ' : 'Score: '; echo $key['user_score'].'</div>
                                        </div>';
                                    }
                                } else {
                                    echo '<div class="review__block"><div class="comment-song">'; echo $lang ? 'Нет обзоров' : 'No reviews'; echo '</div></div>';
                                }
                                echo '
                            </div>
                        </div>
                    </div>
                </div>';
            } else {
                echo '
                <div class="unknown">
                    <div class="error"><i class="fas fa-user-secret"></i></div>
                    <div class="">We can’t find that user.</div>
                </div>';
            }
        echo '
        </div>
    </div>
    <footer class="min-footer"><div>ALTRNTV</div></footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
    <script src="'.ROOT.'/js/script.js"></script>
    <script src="'.ROOT.'/js/profile.js"></script>
</body>
</html>';
?>