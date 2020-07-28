<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
$album = R::getCursor('SELECT albums.*, DATE_FORMAT(albums.date, "%M %d, %Y") as date, genres.genre, users.username, users.url FROM albums INNER JOIN genres ON genres.id=albums.genre INNER JOIN users ON users.id=albums.id_user WHERE users.url=? AND albums.url_album=?', [$_GET['artist'], $_GET['album']]);
$key = $album->getNextItem();

echo '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'.$key['album'].' by '.$key['username'].'</title>
    <link rel="stylesheet" href="'.ROOT.'/css/common.css">
    <link rel="stylesheet" href="'.ROOT.'/css/music-common.css">
    <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.0/css/all.css">
</head>
<body>
    <div id="fullpage">';
        require 'libs/header.php';
        
        echo '
        <div class="content">';
        if ($key) {
            echo '
            <div class="music">
                <div class="music__left">
                    <div class="left">
                        <div id="app">
                            <div class="player">
                                <div class="player-block">
                                    <div class="image">
                                        <span class="music__image" style="background-image: url('.ROOT.'/img/album/'.$key['image_album'].');">
                                            <div class="player__controls">
                                                <div class="player__volume">
                                                    <div class="slider">
                                                        <!--<div class="volume"></div>-->
                                                        <input type="range" id="volume" min="0" max="10" step="0.5" v-model="volume" />
                                                    </div>
                                                </div>

                                                <div class="player__center">
                                                    <div class="player__button player__button--prev" @click="prevTrack">
                                                        <i class="fas fa-step-backward"></i>
                                                    </div>
                                                    <button class="player__button player__button--play" @click="play">
                                                        <i class="fas fa-pause" v-if="isTimerPlaying"></i>
                                                        <i class="fas fa-play" v-else></i>
                                                    </button>
                                                    <div class="player__button player__button--next" @click="nextTrack">
                                                        <i class="fas fa-step-forward"></i>
                                                    </div>
                                                </div>

                                                <div class="player__time" ref="progress">
                                                    <div class="player__time-progress">
                                                        <span class="player__elapsed-time">{{ currentTime }}</span>
                                                        <span class="player__total-time">{{ duration }}</span>
                                                    </div>  
                                                    <div class="player__progress" @click="clickProgress">
                                                        <div class="player__progress-bar" :style="{ width : barWidth }"></div>
                                                    </div>
                                                </div>
                                             </div>
                                        </span>
                                    </div>
                                    <div class="info">
                                        <div class="top-info">'.$key['genre'].'&nbsp;•&nbsp;<span title="'.$key['date'].'">'.substr($key['date'], -4).'</span></div>
                                        <div id="dinfo" class="box" data-id="'.$key['id'].'" data-info="album">
                                            <h1>'.$key['album'].'</h1>
                                            <h3><span>Artist:&nbsp;</span><a href="/'.$key['url'].'">'.$key['username'].'</a></h3>
                                        </div>
                                        <div class="bt">';

                                            $like = R::count('favourite_albums', 'id_album = ?', [$key['id']]);
                                            $user_like = R::count('favourite_albums', 'id_album = ? AND id_user = ?', [$key['id'], $_SESSION['logged_user']->id]);

                                            echo '
                                            <button class="like'; if ($_SESSION['logged_user']) echo ' good'; echo '" data-id="'.$key['id'].'" data-like="'; if ($user_like) echo 'true'; else echo 'false'; echo '"><i class="fas fa-heart"></i><span class="countlike" title="'.$like.'">'.$like.'</span></button>
                                        </div>
                                    </div>
                                    
                                </div>
                                <img id="srcimg" src="'.ROOT.'/img/album/'.$key['image_album'].'">
                            </div>
                            <div class="music-list">';
                            echo '
                                <div>
                                    <div class="song-box" v-for="(currentTrack,index) in tracks" v-bind:class="{ '."active".':isCurrentSong(index) }" v-on:click="changeSong(index)">
                                        <div class="audio" v-if="currentTrackIndex == index && isTimerPlaying">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                        <div class="song-number" v-else>
                                            <span>{{index+1}}</span>
                                        </div>
                                        <div class="song-info">
                                            <span>{{currentTrack.name}}</span>
                                            <span class="explicit" v-if="currentTrack.explicit == 1"></span>
                                        </div>
                                    </div>
                                </div>';
                                echo '
                            </div>';
            
                            $count = (int)R::getCell('SELECT COUNT(*) FROM reviews WHERE id_song=?', [$key['id']]);
                            $sum = (int)R::getCell('SELECT SUM(user_score) FROM reviews WHERE id_song=?', [$key['id']]);

                            if ($count && $sum) {
                                $result = $sum / $count;
                                $result = round($result, 1);
                                $color = floor($result);
                            }
                            echo '
                            <div class="review">
                                <div class="review-top">
                                    <span>'; echo $lang ? 'ОТЗЫВЫ' : 'REVIEWS'; echo '</span>
                                    <a href="'.ROOT.'/about/comment-posting-policy" target="_blank">
                                        <i class="fas fa-info-circle"></i>
                                        <span>'; echo $lang ? 'Правила публикации отзывов' : 'Review Policy'; echo '</span>
                                    </a>
                                </div>
                                <div class="score__block">
                                    <div class="users-score">
                                        <div class="score">'; echo $result ? $result : '0'; echo '</div>
                                        <span>
                                            '; echo $lang ? 'Всего:&nbsp;<i class="fas fa-user"></i>&nbsp;<span>'.$count.'</span>' : '<i class="fas fa-user"></i> <span>'.$count.'</span> total'; echo '
                                        </span>
                                    </div>
                                </div>
                                <div class="reviews">
                                    <div class="reviews-block">';
                                        $reviews = R::getAll('SELECT reviews.*, DATE_FORMAT(reviews.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM reviews INNER JOIN users ON users.id=reviews.id_user WHERE reviews.id_song=? ORDER BY reviews.date DESC', [$key['id']]);

                                        foreach($reviews as $review) {
                                            if ($review['review'] != 'NULL' && $review['username'] != $_SESSION['logged_user']->username) {
                                                echo '
                                                <div class="comment">
                                                    <div class="user-score-block">
                                                        <div class="usec-score-top">
                                                            <div class="user-score-avatar">
                                                                <a href="/'.$review['url'].'">
                                                                    <span class="avatar-image" style="'.image(ROOT.'/img/avatar/'.md5($review['username']).'.jpg').'width: 50px; height: 50px;"></span>
                                                                </a>
                                                            </div>
                                                            <div class="user-score-info">
                                                                <div class="user-score-info-top">
                                                                    <a href="/'.$review['url'].'">
                                                                        <span class="user-score-login">'.$review['username'].'</span>
                                                                    </a>';
                                                                    if ($_SESSION['logged_user']->admin) {
                                                                        echo '
                                                                        <div class="user-score-delete">
                                                                            <span class="delete-score" data-id="'.$review['id'].'" data-review="delete"><i class="fas fa-trash-alt"></i> '; echo $lang ? 'Удалить' : 'Delete'; echo '</span>
                                                                        </div>';
                                                                    }
                                                                echo '
                                                                </div>
                                                                <div class="users-stars-date" style="justify-content: space-between;">
                                                                    <div class="stars">';
                                                                        for ($i = 0; $i < $review['user_score']; $i++) {
                                                                                echo '<div class="star"><i class="fas fa-star"></i></div>';
                                                                            }
                                                                            for ($i = 0; $i < 5 - $review['user_score']; $i++) {
                                                                                echo '<div class="star"><i class="fas fa-star" style="color: #bbb;"></i></div>';
                                                                            }
                                                                    echo '
                                                                    </div>
                                                                    <div class="user-score-date" title="'.$review['date'].'">'.showDate(strtotime($review['date'])).' ago'.'</div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="user-score-text">
                                                            <span>'.$review['review'].'</span>
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        }
                                    echo '
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="music__right">
                    <div class="right-top">
                        <div>
                            <span>'; echo $lang ? 'Похожие' : 'Similar'; echo '</span>
                        </div>
                    </div>';
                    $similar = R::getAll('SELECT albums.album, albums.url_album, albums.image_album, users.username, users.url FROM albums INNER JOIN genres ON genres.id=albums.genre INNER JOIN users ON users.id=albums.id_user WHERE genres.genre=? AND albums.album<>? ORDER BY RAND() LIMIT 3', [$key['genre'], $key['album']]);
                    echo '
                    <div class="similar">';
          			if ($similar) {
                        foreach ($similar as $sim) {
                            echo '
                            <div class="similar-music">
                                <a href="/'.$sim['url'].'/album/'.$sim['url_album'].'">
                                    <div class="similar-image">
                                        <span style="background-image: url(/img/album/'.$sim['image_album'].'); width: 100px; height: 100px;"></span>
                                    </div>
                                    <div class="similar-info">
                                        <span>'.$sim['album'].'</span>
                                        <span>'.$sim['username'].'</span>
                                    </div>
                                </a>
                            </div>';
                        }
          			} else {
                    	echo 'No similarities';
                    }
                    echo '
                    </div>
                    <div class="my-review">';
                    $userreview = R::getAll('SELECT reviews.*, DATE_FORMAT(reviews.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM reviews INNER JOIN users ON users.id=reviews.id_user WHERE reviews.id_song=? AND reviews.id_user=?', [$key['id'], $_SESSION['logged_user']->id]);
                    if ($userreview) {
                        foreach($userreview as $ur) {
                            echo '
                            <div class="my-review-top">
                                <span>'; echo $lang ? 'МОЙ ОТЗЫВ' : 'MY REVIEW'; echo '</span>
                            </div>
                            <div class="my-score-block">
                                <div class="my-score-top">
                                    <div class="user-score-avatar">
                                        <a href="/'.$ur['url'].'">
                                            <span class="avatar-image" style="'.image(ROOT.'/img/avatar/'.md5($ur['username']).'.jpg').'width: 100px; height: 100px;"></span>
                                        </a>
                                    </div>
                                    <div class="my-score-info">
                                        <div class="my-score-info-top">
                                            <a href="/'.$ur['url'].'">
                                                <span class="my-score-login">'.$ur['username'].'</span>
                                            </a>
                                            <div class="user-score-delete">
                                                <span class="delete-score" data-id="'.$ur['id'].'" data-review="delete"><i class="fas fa-trash-alt"></i> '; echo $lang ? 'Удалить' : 'Delete'; echo '</span>
                                            </div>
                                        </div>
                                        <div class="users-stars-date" style="justify-content: space-between;">
                                            <div class="stars">';
                                                for ($i = 0; $i < $ur['user_score']; $i++) {
                                                        echo '<div class="star"><i class="fas fa-star"></i></div>';
                                                    }
                                                    for ($i = 0; $i < 5 - $ur['user_score']; $i++) {
                                                        echo '<div class="star"><i class="fas fa-star" style="color: #bbb;"></i></div>';
                                                    }
                                            echo '
                                            </div>
                                            <div class="user-score-date" title="'.$ur['date'].'">'.showDate(strtotime($ur['date'])).' ago'.'</div>
                                        </div>
                                    </div>
                                </div>';
                                if ($ur['review']) {
                                    echo '<div class="my-score-text">
                                        <span>'.$ur['review'].'</span>
                                    </div>';
                                }
                                echo '
                            </div>';
                        }
                    } else {
                        echo '
                        <div class="write-review">
                            <div class="write-review-top">
                                <span>'; echo $lang ? 'Написать отзыв' : 'Write a Review'; echo '</span>
                            </div>
                            <div class="write-review-block">
                                <textarea class="textarea" placeholder="'; echo $lang ? 'Расскажите другим пользователям, что вы думаете об этой песне!' : 'Tell other users what you think about this song!'; echo '"></textarea>
                                <div class="write-review-bottom">
                                    <div class="score-button-block">
                                        <input type="radio" id="star-5" class="score" name="rating" data-score="5">
                                        <label for="star-5" class="score-button"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star-4" class="score" name="rating" data-score="4">
                                        <label for="star-4" class="score-button"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star-3" class="score" name="rating" data-score="3">
                                        <label for="star-3" class="score-button"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star-2" class="score" name="rating" data-score="2">
                                        <label for="star-2" class="score-button"><i class="fas fa-star"></i></label>
                                        <input type="radio" id="star-1" class="score" name="rating" data-score="1">
                                        <label for="star-1" class="score-button"><i class="fas fa-star"></i></label>
                                    </div>
                                    <button id="btn" class="button" data-id="'.$key['id'].'">'; echo $lang ? 'Опубликовать' : 'Submit'; echo '</button>
                                </div>
                            </div>
                        </div>';
                    }
                    echo '
                </div>
            </div>';
        }
        echo '
        </div>
    </div>
    <footer class="min-footer"><div>ALTRNTV</div></footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
    <script src="'.ROOT.'/js/player.js"></script>
    <script src="'.ROOT.'/js/script.js"></script>
</body>
</html>';
?>