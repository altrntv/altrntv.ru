<?php
require 'libs/db.php';
if ($_SESSION['logged_user']->admin) {
    echo '
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Admin panel</title>
            <link rel="stylesheet" href="css/common.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
            <style>
                .music-box {
                    background: rgba(255, 255, 255, 0.5);
                    border-bottom: 1px solid #d4d4d4;
                    display: flex;
                }

                .music-box:hover {
                    background: white;
                }

                .music-image span {
                    display: block;
                    background-size: cover;
                    background-position: 50% 50%;
                    width: 150px;
                    height: 150px;
                }

                .music-info {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                }

                .music-info h1 {
                    margin: 20px 8px 10px 8px;
                    font-size: 24px;
                    color: black;
                }

                .music-info h3 {
                    margin: 10px 8px 20px 8px;
                    font-size: 16px;
                    color: black;
                }

                .choice {
                    display: flex;
                    align-items: center;
                }

                .choice button {
                    background: none;
                    border: 0;
                    cursor: pointer;
                }

                .choice button i {
                    font-size: 28px;
                }
            </style>
        </head>
        <body>
            <div id="fullpage">';
                require 'libs/header.php';
                echo '
                <div class="content">
                    <div class="music">';
                        $check = R::getCursor('SELECT confirm_songs.*, users.username FROM confirm_songs INNER JOIN users ON users.id=confirm_songs.id_user');

                        while ($row = $check->getNextItem()) {
                            echo '
                                <div class="audio">
                                    <div class="music-box">
                                        <div class="music-image">
                                            <span id="cover" style="background-image: url(/temp/image/'.$row['image_song'].');"></span>
                                        </div>
                                        <div class="music-info">
                                            <h1>'.$row['song'].'</h1>
                                            <h3>'.$row['username']; if ($row['feat']) { echo ' <span class="feat">feat.</span> '.$row['feat']; } echo '</h3>
                                        </div>
                                        <div class="music__block">
                                            <audio controls>
                                                <source src="'.ROOT.'/temp/music/'.$row['music'].'" type="audio/mpeg">
                                                Тег audio не поддерживается вашим браузером. 
                                                <a href="audio/music.mp3">Скачайте музыку</a>.
                                            </audio>
                                        </div>
                                        <div class="choice">
                                            <div class="yes">
                                                <form method="post" action="../libs/validation">
                                                    <button type="submit" name="choice" value="yes">
                                                        <i class="fas fa-check-square"></i>
                                                        <input type="hidden" name="id" value="'.$row['id'].'"/>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="no">
                                                <form method="post" action="../libs/validation">
                                                    <button type="submit" name="choice" value="no">
                                                        <i class="fas fa-window-close"></i>
                                                        <input type="hidden" name="id" value="'.$row['id'].'"/>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                        }
                    echo '
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
            <script src="/js/script.js"></script>
        </body>
    </html>';
} else {
    header("Location: /");
    die();
}
?>