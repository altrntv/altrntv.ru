<?php
require 'libs/db.php'; if ($_SESSION['lang'] == 'ru') { $lang=true; }
if ($_SESSION['logged_user']) {
echo '
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'; echo $lang ? 'Создание альбома' : 'Create album'; echo '</title>
    <link rel="stylesheet" href="'.ROOT.'/css/common.css">
    <link rel="stylesheet" href="'.ROOT.'/css/create.css">
    <link rel="stylesheet" type="text/css" href="'.ROOT.'/css/'.$_SESSION["theme"].'.css" id="theme">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
</head>
<body>
    <div id="fullpage">';
        require 'libs/header.php';
        echo '
        <div class="content">
            <div class="create__content">
                <div class="top-album">
                    <div class="info">
                        <div class="upload-group">
                            <label for="albumname">'; echo $lang ? 'Название альбома' : 'Album name'; echo '</label>
                            <input class="input-album-name" type="text" name="albumname" required>
                        </div>
                        <div class="upload-group">
                            <label for="genresong">'; echo $lang ? 'Жанр' : 'Genre'; echo '</label>';
                            $genres = R::findCollection('genres', 'ORDER BY genre');
                            echo '<select>';
                            while ($row = $genres->next()) {
                                echo '<option value="'.$row["id"].'"> '.$row["genre"].' </option>';
                            }
                            echo '
                            </select>
                        </div>
                    </div>';
                    $image = R::getAll('SELECT DISTINCT image_song FROM songs WHERE id_user=? AND image_song NOT IN (SELECT image_album FROM albums WHERE id_user=?)', [$_SESSION['logged_user']->id, $_SESSION['logged_user']->id]);

                    echo '
                    <div class="album-image">';
                        foreach ($image as $key) {
                            echo '
                        <div class="box">
                            <label class="container rbox">
                                <div class="music-image">
                                    <span style="background-image: url(/img/music/'.$key['image_song'].'); width: 80px; height: 80px; margin: 10px;"></span>
                                </div>
                                <div class="radio">
                                    <input name="image" type="radio" data-image="'.$key['image_song'].'">
                                    <span class="radiomark"></span>
                                </div>
                            </label>
                        </div>
                            ';
                        }
                        echo '
                    </div>
                </div>
                <div class="select__music">
                    <div class="top">
                        <h3>'; echo $lang ? 'Выберете аудиозаписи' : 'Selected song'; echo '</h3>
                        <button id="click" class="button">'; echo $lang ? 'Создать' : 'Create'; echo '</button>
                    </div>';
                    $albums = R::getAll('SELECT album FROM albums WHERE id_user = ?', [$_SESSION['logged_user']->id]);
                    if($albums) {
                        $id = array();
                        foreach ($albums as $key) { $id[] = $key['album']; }
                        $songs = R::getAll('SELECT songs.*, users.username FROM songs INNER JOIN users ON users.id=songs.id_user WHERE songs.id_user = '.$_SESSION['logged_user']->id.' AND songs.album NOT IN ('. R::genSlots($id) .')', $id);    
                    } else {
                        $songs = R::getAll('SELECT songs.*, users.username FROM songs INNER JOIN users ON users.id=songs.id_user WHERE songs.id_user = ? ', [$_SESSION['logged_user']->id]); 
                    }
                    if ($songs) {
                        echo '<div class="music-list">';
                        foreach($songs as $row) {
                            echo '
                            <div class="music-box">
                                <label class="container cbox">
                                    <div class="checkbox">
                                        <input type="checkbox" data-id="'.$row['id'].'">
                                        <span class="checkmark"></span>
                                    </div>
                                    <div class="music-image">
                                        <span style="background-image: url('.ROOT.'/img/music/'.$row['image_song'].'); width: 100px; height: 100px;"></span>
                                    </div>
                                    <div class="music-info">
                                        <span>'.$row['song'].'</span>
                                        <span>'.$row['username'].'</span>
                                        <span>'.$row['album'].'</span>
                                    </div>
                                </label>
                            </div>';    
                        }
                        echo '</div>';
                    } else {
                        echo '
                        <div class="no__audio">
                            <p>'; echo $lang ? 'Нет доступных аудиозаписей' : 'No audio recordings available'; echo '</p>
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
    <script>
    $("#click").click(function() {
      var id = [];
      $(".cbox input:checked").each(function(i, item) {
        id.push($(item).data("id"));
      });
      var name = $(".input-album-name").val();
      var image = $(".rbox input:checked").data("image");
      var genre = $(".upload-group select").val();
      $.ajax({
            url: "../libs/create", 
            type: "POST",
            data: {id:id, name:name, genre:genre, image:image},
            dataType: "json", 
            success: function(result) {
                if (result.error){
                    alert(result.message);
                    location.reload();
                } else {
                    alert(result.message);
                }
            }
        });
    });
    </script>
</body>
</html>';
} else {
    header("Location: /");
    die();
}
?>