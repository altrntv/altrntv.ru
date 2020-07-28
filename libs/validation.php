<?php

require 'db.php';
$data = $_POST;

if ($data) {
    $song = R::getRow('SELECT * FROM confirm_songs WHERE id = ? LIMIT 1', [ $data['id'] ]);

    if ($data['choice'] == 'yes') {

        R::exec("INSERT INTO songs (id_user, feat, song, album, url_song, image_song, music, cca3, explicit, genre, date) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [$song['id_user'], $song['feat'], $song['song'], $song['album'], $song['url_song'], $song['image_song'], $song['music'], $song['cca3'], $song['explicit'], $song['genre'], $song['date']]);
        $id = R::getInsertID();
        
        $headers = get_headers(ROOT.'/temp/image/'.$song['image_song']);
        $headers2 = get_headers(ROOT.'/temp/music/'.$song['music']);
        
        if(strpos($headers[0], '200')) {
            rename('../temp/image/'.$song['image_song'], '../img/music/'.$song['image_song']);
        }
        if(strpos($headers2[0], '200')) {
            rename('../temp/music/'.$song['music'], '../music/'.$song['music']);
        }

        R::exec('DELETE FROM confirm_songs WHERE id = ?', [$data['id']]);
        notifications($song['id_user'], $_SESSION['logged_user']->id, 'vyes-'.$id);
    } else if ($data['choice'] == 'no') {
        
        $headers = get_headers(ROOT.'/temp/image/'.$song['image_song']);
        $headers2 = get_headers(ROOT.'/temp/music/'.$song['music']);
        
        if(strpos($headers[0], '200')) {
            unlink('../temp/image/'.$song['image_song']);
        }
        if(strpos($headers2[0], '200')) {
            unlink('../temp/music/'.$song['music']);
        }

        R::exec('DELETE FROM confirm_songs WHERE id = ?', [$data['id']]);
        notifications($song['id_user'], $_SESSION['logged_user']->id, 'vno');
    }
    
    header("Location: /check");
    die();
}
?>