<?php
    require 'db.php';

    if (isset($_POST['query'])){
        $search = $_POST['query'];
        
        $user = R::getAll('SELECT * FROM users WHERE username LIKE ? LIMIT 2', ["%$search%"]);
        $album = R::getAll('SELECT albums.*, users.username, users.url FROM albums INNER JOIN users ON users.id=albums.id_user WHERE users.username LIKE :search OR albums.album LIKE :search LIMIT 2', ['search' => "%$search%"]);
        $song = R::getAll('SELECT songs.*, users.username, users.url FROM songs INNER JOIN users ON users.id=songs.id_user WHERE users.username LIKE :search OR songs.song LIKE :search LIMIT 6', ['search' => "%$search%"]);
        
        echo '<a href="'.ROOT.'/search?q='.$search.'" class="list-group-item-action">Search for «'.$search.'»</a>';
        
        foreach($user as $row) {
            echo '
            <div class="search__item">
                <a href="/'.$row['url'].'" class="list-group-item">
                    <span class="avatar-image" style="width: 20px; height: 20px;'.image(ROOT.'/img/avatar/'.md5($row['username']).'.jpg').' margin-right: 7px;"></span>
                    <span class="search__info">'.$row['username'].'</span>
                    <span class="search__icon"><i class="fas fa-user"></i></span>
                </a>
              </div>';
        }
        
        foreach($album as $row) {
            echo '
            <div class="search__item">
                <a href="'.ROOT.'/'.$row['url'].'/album/'.$row['url_album'].'" class="list-group-item">
                    <span class="search-image" style="width: 20px; height: 20px; background-image: url('.ROOT.'/img/album/'.$row['image_album'].'); background-size: cover; background-position: 50% 50%; margin-right: 7px;"></span>
                    <span class="search__info">'.$row['username'].' - '.$row['album'].'</span>
                    <span class="search__icon"><i class="fas fa-compact-disc"></i></span>
                </a>
               </div>';
        }
        
        foreach($song as $row) {
            echo '<div class="search__item">
                    <a href="'.ROOT.'/'.$row['url'].'/'.$row['url_song'].'" class="list-group-item">
                        <span class="search-image" style="width: 20px; height: 20px; background-image: url('.ROOT.'/img/music/'.$row['image_song'].'); background-size: cover; background-position: 50% 50%; margin-right: 7px;"></span>
                        <span class="search__info">'.$row['username'].' - '.$row['song']; if ($row['feat']) echo ' <span class="feat">feat.</span> '.$row['feat']; echo '</span>
                        <span class="search__icon"><i class="fas fa-music"></i></span>
                    </a>
                   </div>';
        }
        if (!$user && !$album && !$song) {
            echo '
            <div class="search__item">
                <p>Nothing found</p>
            </div>';
        }
    }

?>