<?php require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; } ?>
   <html>
    <head>
        <meta charset="UTF-8">
        <title>ALTRNTV</title>
        <link rel="stylesheet" href="css/common.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" type="text/css" href="<?php echo ROOT; ?>/css/<?php echo $_SESSION["theme"]; ?>.css" id="theme">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    </head>
    <body>
        <div id="fullpage">
            <?php
                require 'libs/header.php'
            ?>
            <div class="content">
                <div class="release__block">
                    <div class="release__head">
                        <div class="release__image">
                            <a><img src="img/music/mystery_jetsa_billion_heartbeats.jpg"></a>
                        </div>
                        <div class="release__info">
                            <div>
                                <span class="tag">Alternative</span>
                            </div>
                            <div>
                                <h1>Endless City</h1>
                                <h2><a href="/mystery-jets">Mystery Jets</a></h2>
                                <p><?php echo $lang ? 'Дата выхода: 3 апреля 2019 г.' : 'Released April 3, 2020'; ?></p>
                            </div>
                            <a href="/mystery-jets/endless-city">
                                <button class="button"><?php echo $lang ? 'Слушать' : 'Listen'; ?></button>
                            </a>
                        </div>
                    </div>
                    <div class="release__body">
                        <h2><?php echo $lang ? 'Новые альбомы' : 'New Albums'; ?></h2>
                        <article id="slider">
                            <input checked type="radio" name="slider" id="switch1">
                            <input type="radio" name="slider" id="switch2">
                            <input type="radio" name="slider" id="switch3">
                            <div id="slides">
                                <div id="overflow">
                                    <div class="slides-nr">
                                        <?php
                                            $albums = R::getCursor('SELECT users.url, users.username, albums.album, albums.image_album, albums.url_album FROM albums INNER JOIN users ON albums.id_user = users.id ORDER BY albums.id DESC LIMIT 18');
                                            
                                            for ($i = 0; $i < 3; $i++) {
                                                $i2 = 0;
                                                echo '<article>
                                                      <div class="new-releases">
                                                      <ul>';
                                                while ($i2 < 6) {
                                                    $box = $albums->getNextItem();
                                                    echo '
                                                    <li class="box">
                                                        <a href="'.$box['url'].'/album/'.$box['url_album'].'">
                                                            <div class="box-image">
                                                                <img src="img/album/'.$box['image_album'].'">
                                                            </div>
                                                            <div class="box-song">
                                                                <label class="nr-name">'.$box['album'].'</label>
                                                            </div>
                                                        </a>
                                                        <div class="box-artist">
                                                            <a href="/'.$box['url'].'" class="nr-artist nr-link">'.$box['username'].'</a>
                                                        </div>
                                                    </li>';
                                                    $i2++;
                                                }
                                                echo '</ul>
                                                      </div>
                                                      </article>';
                                            }
                                            $albums->close();
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div id="controls">
                                <label for="switch1"></label>
                                <label for="switch2"></label>
                                <label for="switch3"></label>
                            </div>
                        </article>
                    </div>
                    <div class="release__footer">
                        <div class="slogan">
                            <p>Somedays, look around, living life in a dream.</p>
                        </div>
                    </div>
                </div>
                
                <div class="music__block">
                    <div class="music__list">
                        <?php
                            if ($_SESSION['logged_user']) {
                                echo '<h4 class="item__h4">'; echo $lang ? 'Рекомендации' : 'Recommended'; echo '</h4>';
                                $array = recommended();
                                if ($array) {
                                    $rsong = R::getAll("SELECT users.url, users.username, songs.song, songs.url_song, songs.image_song, songs.feat FROM songs INNER JOIN users ON songs.id_user = users.id WHERE songs.id IN (". R::genSlots($array) .")", $array);
                                    echo '
                                    <div class="new-releases">
                                        <ul>';
                                        foreach ($rsong as $key) {
                                            echo '
                                                <li class="box">
                                                    <a href="/'.$key['url'].'/'.$key['url_song'].'">
                                                        <div class="box-image">
                                                            <img src="img/music/'.$key['image_song'].'">
                                                        </div>
                                                        <div class="box-song">
                                                            <label class="nr-name nr-link">'.$key['song'].'</label>
                                                        </div>         
                                                    </a>
                                                    <div class="box-artist">
                                                        <a href="/'.$key['url'].'" class="nr-artist nr-link">'.$key['username']; if ($key['feat']) { echo ' <span class="feat">feat.</span> '.$key['feat']; } echo '</a>
                                                    </div>
                                                </li>';  
                                        }
                                    echo '
                                        </ul>
                                    </div>';
                                } else {
                                    echo '<p>'; echo $lang ? 'У Вас нет рекомендаций, добавляйте аудиозаписи в избранное, чтобы они появились!' : 'If you don&#039t have any recommendations, add audio recordings to your favorites so that they appear!'; echo '</p>';
                                }
                            }
                        
                            $genres = R::findCollection('genres', 'LIMIT 4');
                            $check = 0;
                            while ($genre = $genres->next()) {
                                echo '<h4 class="item__h4">'.$genre->genre.'</h4>
                                <div class="new-releases">
                                    <ul>';
                                        $songs = R::getCursor('SELECT users.url, users.username, songs.song, songs.url_song, songs.image_song, songs.feat FROM songs INNER JOIN users ON songs.id_user = users.id WHERE songs.genre = ? ORDER BY songs.id DESC LIMIT 5', [$genre->id]);

                                        while ($row = $songs->getNextItem()) {
                                            echo '<li class="box">
                                                    <a href="/'.$row['url'].'/'.$row['url_song'].'">
                                                        <div class="box-image">
                                                            <img src="img/music/'.$row['image_song'].'">
                                                        </div>
                                                        <div class="box-song">
                                                            <label class="nr-name nr-link">'.$row['song'].'</label>
                                                        </div>         
                                                    </a>
                                                    <div class="box-artist">
                                                        <a href="/'.$row['url'].'" class="nr-artist nr-link">'.$row['username']; if ($row['feat']) { echo ' <span class="feat">feat.</span> '.$row['feat']; } echo '</a>
                                                    </div>
                                                  </li>';  
                                        } 
                                        $songs->close();
                                    echo '</ul>
                                </div>';
                                if ($check < 3) echo '<hr>';
                                $check++;
                                if ($check == 1) {
                                    $last = R::getCursor('SELECT users.url, users.username, songs.song, songs.url_song, songs.image_song, songs.feat FROM songs INNER JOIN users ON songs.id_user = users.id ORDER BY RAND() LIMIT 10');
                                    echo '<h4 class="item__h4" style="margin-bottom: 0;">The Upload</h4>
                                    <p>'; echo $lang ? 'Новые опубликованные треки' : 'Newly posted tracks'; echo '</p>
                                    <div class="new-releases">
                                        <div class="new-tracks-list">
                                            <div class="image-tracks-list">
                                                <div></div>
                                            </div>
                                            <div class="tracks">';
                                                while ($rowlist = $last->getNextItem()) {
                                                    echo '
                                                    <a href="/'.$rowlist['url'].'/'.$rowlist['url_song'].'">
                                                        <div class="track">
                                                            <span>'.$rowlist['username']; if ($rowlist['feat']) { echo ' <span class="feat">feat.</span> '.$rowlist['feat']; } echo ' —</span>
                                                            <span>'.$rowlist['song'].'</span>
                                                        </div>
                                                    </a>';
                                                }
                                            echo '
                                            </div>
                                        </div>
                                    </div>
                                    <hr>';
                                }
                            }
                        ?>
                        </div>
                    <div class="genres__block">
                        <h4 class="item__h4 bg__purple"><?php echo $lang ? 'Жанры' : 'Genres'; ?></h4>
                        <ul>
                            <?php
                                $genres = R::findCollection('genres', 'ORDER BY genre');

                                while ($row = $genres->next()) {
                                    echo '<li><a href="'.ROOT.'/genres/'.mb_strtolower($row['genre']).'">'.$row['genre'].'</a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </div>
                
                <div class="popular__block">
                    <div class="popular__left">
                        <h4 align="right" class="item__h4 before__custom"><?php echo $lang ? 'Самые популярные' : 'Most popular'; ?></h4>
                        <div class="rating">
                            <?php
                                $maxv = R::findOne('songs', 'ORDER BY visits DESC LIMIT 1');
                                $percent = $maxv->visits;
                                $top = R::getCursor('SELECT users.url, users.username, songs.song, songs.image_song, songs.visits FROM songs INNER JOIN users ON songs.id_user = users.id ORDER BY visits DESC LIMIT 5');
                                
                                while ($row = $top->getNextItem()) {
                                    $style = ($row['visits'] * 100) / $percent;
                                    echo '<div class="rating-song">
                                               <div class="rating-image">
                                                   <img src="img/music/'.$row['image_song'].'">
                                               </div>
                                               <div class="rating-info">
                                                    <h1>'.$row['song'].'</h1>
                                                    <h3>'.$row['username'].'</h3>
                                                    <div class="percent"><div style="background: linear-gradient(0.25turn, transparent, #f50);height: 100px;width: '.$style.'%"></div></div>
                                               </div>
                                           </div>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="popular__right">
                        <h4 class="item__h4 bg__dsblue"><?php echo $lang ? 'Релиз недели' : 'Release of the week'; ?></h4>
                        <div class="release-week">
                            <div></div>
                        </div>
                        <div class="info-release-week">
                            <div><h2>Glitterbug</h2></div>
                            <div><h3>The Wombats</h3></div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="footer-block">
                    <div class="footer-info">
                        <ul>
                            <li><a href="help"><i class="fas fa-question-circle"></i><?php echo $lang ? 'ПОМОЩЬ' : 'HELP'; ?></a></li>
                            <li><a id="click"><i class="fas fa-comments"></i><?php echo $lang ? 'СВЯЗАТЬСЯ С НАМИ' : 'TALK TO US'; ?></a></li>
                            <li><a href="http://feedback.altrntv.dx.am" target="_blank"><i class="fas fa-arrow-alt-circle-up"></i><?php echo $lang ? 'ПРЕДЛОЖЕНИЯ ПО УЛУЧШЕНИЮ' : 'FEEDBACK'; ?></a></li>
                        </ul>
                        <div class="language">
                            <form action="libs/settings" method="post">
                                <label for="lang"><?php echo $lang ? 'Язык' : 'Language'; ?>:</label>
                                <select id="lang" name="lang" onchange='this.form.submit()'>
                                    <?php if ($_SESSION['lang'] == 'ru') : ?>
                                    <option value="en"><?php echo $lang ? 'Английский' : 'English'; ?></option>
                                    <option value="ru" selected><?php echo $lang ? 'Русский' : 'Russian'; ?></option>
                                    <?php else : ?>
                                    <option value="en" selected><?php echo $lang ? 'Английский' : 'English'; ?></option>
                                    <option value="ru"><?php echo $lang ? 'Русский' : 'Russian'; ?></option>
                                    <?php endif; ?>
                                </select>
                            </form>
                        </div>
                        
                    </div>
                    <div class="footer-link">
                        <div class="copyright">
                            <span>2019-2020 © <b>ALTRNTV</b></span>
                        </div>
                    </div>
                </div>
            </footer>
            <div class="ttu">
                <div class="ttu-header">
                    <h3><?php echo $lang ? 'Сообщение' : 'Message Support'; ?></h3>
                    <button id="button-close"><i class="fas fa-window-close"></i></button>
                </div>
                
                <div class="form-group row">
                    <div class="form-name">
                        <label for="nm"><?php echo $lang ? 'Имя' : 'Name'; ?></label>
                    </div>
                    <div class="form-name-input">
                        <input id="nm" type="text" name="name" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="form-email">
                        <label for="em">E-mail</label>
                    </div>
                    <div class="form-email-input">
                        <input id="em" type="email" name="email" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tx" class="mes"><?php echo $lang ? 'Опишите проблему' : 'Message'; ?></label>
                    <textarea id="tx" name="message-text"></textarea>
                </div>
                <div class="form-group">
                    <input id="btn" type="button" class="button invert" value="<?php echo $lang ? 'Отправить' : 'Submit'; ?>">
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $("#click").click(function () {
            $(".ttu").toggleClass("active");
        });

        $("#button-close").click(function () {
            $(".ttu").removeClass("active");
        });   
    </script>
</html>