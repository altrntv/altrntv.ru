<?php
require 'db.php';
if ($_GET['id']) {
    $id = $_GET['id']; 
    $page = (int)$_GET['page'];
    $count = (int)$_GET['count'];
    $start = ($page - 1) * $count;
    //$songs = R::getAll("SELECT * FROM songs ORDER BY id LIMIT ?, ?", [$start, $count]);
    $songs = R::getAll("SELECT * FROM songs WHERE id_user = ? ORDER BY id LIMIT ?, ?", [$id, $start, $count]);
    $array = array();

    foreach($songs as $row) {
        $song = array 
        (
            "id" => $row['id'],
            "artist" => $row['artist'],
            "feat" => $row['feat'],
            "song" => str_replace("&#039", "'", $row['song']),
            "album" => $row['album'],
            "image" => 'http://altrntv.net/img/music/'.$row['image_song'],
            "music" => $row['music'],
            "cca3" => $row['cca3'],
            "explicit" => $row['explicit'],
            "genre" => $row['genre'],
            "date" => $row['date_release']
        );
        array_push($array, $song);
    }

    echo json_encode($array);
}
?>