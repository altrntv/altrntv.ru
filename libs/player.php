<?php
    require '../libs/db.php';
    if ($_POST['info'] == 'album' && $_POST['id']) {
        $album = R::getCursor('SELECT songs.song, songs.music, songs.explicit, users.url, albums.url_album FROM songs INNER JOIN albums ON albums.album=songs.album INNER JOIN users ON users.id=songs.id_user WHERE albums.id=? ORDER BY number', [$_POST['id']]);
        $array = array();
        while ($row = $album->getNextItem()) {
            $array[] = array(
                'artist' => $row['url'],
                'name' => str_replace('&#039', "'", $row['song']),
                'source' => ROOT.'/album/'.$row['url'].'/'.$row['url_album'].'/'.$row['music'],
                'explicit' => $row['explicit'],
            );
        }

        header('Content-Type: text/json; charset=utf-8');
        echo json_encode($array);
    } else if ($_POST['info'] == 'song' && $_POST['id']) {
        $song = R::getCursor('SELECT users.username, songs.song, songs.music, songs.explicit FROM songs INNER JOIN users ON users.id=songs.id_user WHERE songs.id=?', [$_POST['id']]);
        $array = array();
        while ($row = $song->getNextItem()) {
            $array[] = array(
                'artist' => $row['username'],
                'name' => str_replace("&#039", "'", $row['song']),
                'source' => ROOT.'/music/'.$row['music'],
                'explicit' => $row['explicit'],
            );
        }

        header('Content-Type: text/json; charset=utf-8');
        echo json_encode($array);
    } else {
        header("Location: /");
        die();
    }
?>