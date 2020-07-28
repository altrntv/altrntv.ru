<?php
require 'db.php';
$data = $_POST;
if ($data) {
    $id_user = $_SESSION['logged_user']->id;
    $album = str_replace("'", '&#039', $data['name']);
    $url = translit(transform($data['name']));
    $genre = $data['genre'];
    $date = date("Y-m-d");
    if($data['image']) {
        $image = $data['image'];
        copy('../img/music/'.$image, '../img/album/'.$image);
    } else {
        $image = transform($_SESSION['logged_user']->username).'-'.transform($_POST['name'] ).'.jpg';
        $image = str_replace('-', '_', $image);   
    }
    
    R::exec("UPDATE songs SET image_song = '".$image."', album = '".$album."' WHERE id IN (". R::genSlots($data['id']) .")", $data['id']);
    R::exec('INSERT INTO albums (id_user, album, genre, date, image_album, url_album) VALUES(?, ?, ?, ?, ?, ?)', [$id_user, $album, $genre, $date, $image, $url]);
    $music = R::getCol("SELECT music FROM songs WHERE id IN (". R::genSlots($data['id']) .")", $data['id']);
    
    $dirlogin = transform($_SESSION['logged_user']->username);
    $diralbum = transform($data['name']);
    
    if (is_dir('../album/'.$dirlogin)) {
        mkdir('../album/'.$dirlogin.'/'.$diralbum, 0777);
    } else {
        mkdir('../album/'.$dirlogin, 0777);
        mkdir('../album/'.$dirlogin.'/'.$diralbum, 0777);
    }
    
    if (is_dir('../album/'.$dirlogin.'/'.$diralbum)) {
        foreach ($music as $key) {
            copy('../music/'.$key, '../album/'.$dirlogin.'/'.$diralbum.'/'.$key);
            unlink('../music/'.$key);
        }
    }
    $out = array(
        'error' => true,
        'message' => 'success',
    );
    header('Content-Type: text/json; charset=utf-8');
    echo json_encode($out); 
} else if (!$data['id'] || !$data['name']) {
    $error = false;
    $message = 'error';

    $out = array(
        'error' => $error,
        'message' => $message,
    );
    header('Content-Type: text/json; charset=utf-8');
    echo json_encode($out);
} else {
    header("Location: /");
    die();
}

?>