<?php
require 'db.php';
if ($_SESSION['lang'] == 'ru') { $lang=true; }
if ($_SESSION['logged_user'] && $_POST['do'] == 1) {
    $notifications = R::getAll('SELECT notifications.*, DATE_FORMAT(notifications.date, "%M %d, %Y %H:%i:%s") as date, users.username, users.url FROM notifications INNER JOIN users ON users.id=notifications.id_sender WHERE id_recipient = ? AND status<>0 ORDER BY date DESC LIMIT 3', [$_SESSION['logged_user']->id]);
    
    if ($notifications) {
        foreach($notifications as $row) {
            $info = explode('-', $row['text']);
            echo '
            <div class="notification__item unread" data-id="'.$row['id'].'">
                <div class="notification__top">
                    <a href="'.ROOT.'/'.$row['url'].'">
                        <div class="notification__user">
                            <span class="avatar-image" style="width: 20px; height: 20px;'.image(ROOT.'/img/avatar/'.md5($row['username']).'.jpg').' margin-right: 7px;"></span>
                            <span>'.$row['username'].'</span>
                        </div>
                    </a>
                    <div class="notification__time">
                        <span title="'.$row['date'].'">'.showDate(strtotime($row['date'])).' ago</span>
                    </div>
                </div>
                <div class="notification__bottom">';
                    if ($info[0] == 'review') {
                        $song = R::getRow('SELECT song, image_song FROM songs WHERE `id` = ? LIMIT 1', [$info[1]]);
                        echo $lang ? '<p>Оценил вашу песню!</p>' : '<p>Appreciated your song!</p>';
                        echo '
                        <div class="notification__song">
                            <span class="search-image" style="width: 20px; height: 20px; background-image: url('.ROOT.'/img/music/'.$song['image_song'].'); background-size: cover; background-position: 50% 50%; margin-right: 7px;"></span>
                            <span>'.$_SESSION['logged_user']->username.' - '.$song['song'].'</span>
                        </div>';
                    } else if ($info[0] == 'vyes') {
                        $song = R::getRow('SELECT song, image_song FROM songs WHERE `id` = ? LIMIT 1', [$info[1]]);
                        echo '<p style="color: #2ecc71;">';
                        echo $lang ? 'Одобрил вашу песню!' : 'Approved your song!';
                        echo '</p>
                        <div class="notification__song">
                            <span class="search-image" style="width: 20px; height: 20px; background-image: url('.ROOT.'/img/music/'.$song['image_song'].'); background-size: cover; background-position: 50% 50%; margin-right: 7px;"></span>
                            <span>'.$_SESSION['logged_user']->username.' - '.$song['song'].'</span>
                        </div>';
                    } else if ($info[0] == 'vno') {
                        echo '<p style="color: #ff3f34;">';
                        echo $lang ? 'Отклонил вашу песню!' : 'Rejected your song!';
                        echo '</p>';
                    }
                echo '
                </div>
            </div>';
        }   
    } else {
        echo '
        <div class="notification__noitem">
            <p align="center">'; echo $lang ? 'Нет уведомлений' : 'No notifications'; echo '</p>
        </div>';
    }
} else if ($_SESSION['logged_user'] && $_POST['id'] && $_POST['do'] == 2) {
    R::exec('UPDATE `notifications` SET `status` = 0 WHERE id = ?', [$_POST['id']]);
}

?>